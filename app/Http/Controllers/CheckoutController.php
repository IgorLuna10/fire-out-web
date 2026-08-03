<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $cart = json_decode($request->input('cart', '[]'), true);
        if (empty($cart)) return redirect()->route('produtos');

        $request->session()->put('checkout_cart', json_encode($cart));
        return redirect()->route('checkout');
    }

    public function index(Request $request)
    {
        $cart = json_decode($request->session()->get('checkout_cart', '[]'), true);
        if (empty($cart)) return redirect()->route('produtos');

        $user           = Auth::user();
        $defaultAddress = $user ? $user->defaultAddress()->first() : null;
        $cartSubtotal   = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));

        return view('pages.checkout', compact('cart', 'user', 'defaultAddress', 'cartSubtotal'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|min:2|max:100',
            'email'           => 'required|email|max:150',
            'telefone'        => 'required|string|min:8|max:20',
            'document_type'   => 'required|in:cpf,cnpj',
            'document_number' => ['required', 'string', function ($_attr, $val, $fail) use ($request) {
                $digits = preg_replace('/\D/', '', $val);
                if ($request->document_type === 'cpf'  && strlen($digits) !== 11) $fail('CPF inválido — deve ter 11 dígitos.');
                if ($request->document_type === 'cnpj' && strlen($digits) !== 14) $fail('CNPJ inválido — deve ter 14 dígitos.');
            }],
            'pagamento' => 'required|in:consulta',
            'parcelas'  => 'nullable|integer|min:1|max:4',
            'rua'       => 'nullable|string|max:150',
            'numero'    => 'nullable|required_with:rua|string|max:20',
            'bairro'    => 'nullable|required_with:rua|string|max:100',
            'cidade'    => 'nullable|required_with:rua|string|max:100',
            'estado'    => 'nullable|required_with:rua|string|size:2',
            'cep'       => 'nullable|required_with:rua|string|max:9',
        ]);

        $cart = json_decode($request->session()->get('checkout_cart', '[]'), true);
        if (empty($cart)) return redirect()->route('produtos');

        $subtotal    = collect($cart)->sum(fn($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        $hasConsulta = collect($cart)->contains(fn($i) => !isset($i['price']) || $i['price'] === null);

        $order      = null;

        DB::transaction(function () use ($request, $cart, $subtotal, $hasConsulta, &$order) {
            $order = Order::create([
                'order_number'      => Order::generateNumber(),
                'user_id'           => Auth::id(),
                'status'            => 'pending',
                'customer_name'     => $request->nome,
                'customer_email'    => $request->email,
                'customer_phone'    => $request->telefone,
                'company'           => $request->empresa,
                'document_type'     => $request->document_type,
                'document_number'   => $request->document_number,
                'ship_street'       => $request->rua,
                'ship_number'       => $request->numero,
                'ship_complement'   => $request->complemento,
                'ship_neighborhood' => $request->bairro,
                'ship_city'         => $request->cidade,
                'ship_state'        => $request->estado,
                'ship_zipcode'      => $request->cep,
                'payment_method'    => $request->pagamento,
                'installments'      => $request->parcelas ?? 1,
                'notes'             => $request->mensagem,
                'subtotal'          => $subtotal,
                'has_consulta'      => $hasConsulta,
            ]);

            foreach ($cart as $item) {
                $product   = Product::where('sku', $item['prodId'])->first();
                $unitPrice = $product?->price;
                $qty       = $item['qty'] ?? 1;
                $itemSub   = $unitPrice !== null ? $unitPrice * $qty : 0;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_sku'  => $item['prodId'],
                    'product_name' => $item['name'],
                    'variant'      => $item['variant'] ?? null,
                    'unit_price'   => $unitPrice,
                    'qty'          => $qty,
                    'subtotal'     => $itemSub,
                ]);

                if ($product && $product->stock_qty > 0) {
                    $product->decrement('stock_qty', min($qty, $product->stock_qty));
                }
            }
        });

        // Enviar e-mail de notificação de pedido (Consulta de Pagamento)
        $emailBody = "NOVO PEDIDO DE ORÇAMENTO RECEBIDO\n";
        $emailBody .= "==========================================\n";
        $emailBody .= "Número do Pedido: #" . $order->order_number . "\n";
        $emailBody .= "Cliente: " . $order->customer_name . "\n";
        $emailBody .= "E-mail: " . $order->customer_email . "\n";
        $emailBody .= "Telefone: " . $order->customer_phone . "\n";
        if ($order->company) {
            $emailBody .= "Empresa: " . $order->company . "\n";
        }
        $emailBody .= "Documento: " . strtoupper($order->document_type) . " - " . $order->document_number . "\n\n";

        if ($order->ship_street) {
            $emailBody .= "Endereço de Entrega:\n";
            $emailBody .= "Rua: " . $order->ship_street . ", nº " . $order->ship_number . "\n";
            if ($order->ship_complement) {
                $emailBody .= "Complemento: " . $order->ship_complement . "\n";
            }
            $emailBody .= "Bairro: " . $order->ship_neighborhood . "\n";
            $emailBody .= "Cidade: " . $order->ship_city . " - " . $order->ship_state . "\n";
            $emailBody .= "CEP: " . $order->ship_zipcode . "\n\n";
        }

        $emailBody .= "Itens do Pedido:\n";
        $emailBody .= "------------------------------------------\n";
        foreach ($cart as $item) {
            $itemPrice = isset($item['price']) && $item['price'] !== null ? 'R$ ' . number_format($item['price'], 2, ',', '.') : 'Sob Consulta';
            $itemSub = isset($item['price']) && $item['price'] !== null ? 'R$ ' . number_format($item['price'] * ($item['qty'] ?? 1), 2, ',', '.') : 'Sob Consulta';
            $emailBody .= "- " . ($item['qty'] ?? 1) . "x " . $item['name'];
            if (!empty($item['variant'])) {
                $emailBody .= " (" . $item['variant'] . ")";
            }
            $emailBody .= " | Preço unitário: " . $itemPrice . " | Subtotal: " . $itemSub . "\n";
        }
        $emailBody .= "------------------------------------------\n";
        $emailBody .= "Subtotal Geral: R$ " . number_format($subtotal, 2, ',', '.') . "\n\n";

        if ($order->notes) {
            $emailBody .= "Observações do Cliente:\n";
            $emailBody .= $order->notes . "\n";
        }

        $dadosEmail = [
            'nome' => $order->customer_name,
            'sobrenome' => '',
            'email' => $order->customer_email,
            'mensagem' => $emailBody,
        ];

        try {
            Mail::to(['contato@fireout.com.br', 'fireout@fireout.com.br'])
                ->send(new \App\Mail\ContatoMail($dadosEmail));
        } catch (\Exception $e) {
            Log::error('Erro ao enviar e-mail de pedido: ' . $e->getMessage());
        }

        $request->session()->forget('checkout_cart');
        $request->session()->put('last_order', $this->buildSessionOrder($order, $cart, $subtotal, $hasConsulta));

        return redirect()->route('pedido-confirmado');
    }

    private function buildSessionOrder(Order $order, array $cart, float $subtotal, bool $hasConsulta): array
    {
        return [
            'numero'       => $order->order_number,
            'nome'         => $order->customer_name,
            'email'        => $order->customer_email,
            'telefone'     => $order->customer_phone,
            'pagamento'    => $order->payment_method,
            'parcelas'     => $order->installments,
            'subtotal'     => $subtotal,
            'has_consulta' => $hasConsulta,
            'criado_em'    => now()->format('d/m/Y H:i'),
            'cart'         => $cart,
        ];
    }

    public function confirmado(Request $request)
    {
        $order = $request->session()->get('last_order');
        if (!$order) return redirect()->route('produtos');

        // Build WhatsApp text
        $waText = "Olá! Fiz um pedido de orçamento no site da Fire Out.\n\n";
        $waText .= "*Pedido:* #" . $order['numero'] . "\n";
        $waText .= "*Cliente:* " . $order['nome'] . "\n";
        $waText .= "*E-mail:* " . $order['email'] . "\n";
        $waText .= "*Telefone:* " . $order['telefone'] . "\n\n";
        $waText .= "*Itens:* \n";
        foreach ($order['cart'] as $item) {
            $priceStr = isset($item['price']) && $item['price'] !== null 
                ? "R$ " . number_format($item['price'], 2, ',', '.') 
                : "Sob Consulta";
            $waText .= "• " . ($item['qty'] ?? 1) . "x " . $item['name'];
            if (!empty($item['variant'])) {
                $waText .= " (" . $item['variant'] . ")";
            }
            $waText .= " - " . $priceStr . "\n";
        }
        $waText .= "\n*Subtotal:* R$ " . number_format($order['subtotal'], 2, ',', '.') . "\n\n";
        $waText .= "Gostaria de consultar as opções de pagamento e finalizar o meu pedido.";

        $waUrl = "https://wa.me/5521966969836?text=" . rawurlencode($waText);

        return view('pages.pedido-confirmado', compact('order', 'waUrl'));
    }

    // ── Webhook from Mercado Pago ─────────────────────────────────────────────
    public function webhook(Request $request)
    {
        $type = $request->input('type');
        if ($type !== 'payment') return response()->json(['ok' => true]);

        $paymentId = $request->input('data.id');
        if (!$paymentId) return response()->json(['ok' => true]);

        try {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));
            $client  = new PaymentClient();
            $payment = $client->get((int) $paymentId);

            $orderNumber = $payment->external_reference;
            $status      = match ($payment->status) {
                'approved'   => 'paid',
                'cancelled'  => 'cancelled',
                'rejected'   => 'payment_failed',
                default      => 'awaiting_payment',
            };

            Order::where('order_number', $orderNumber)->update(['status' => $status]);

        } catch (\Exception $e) {
            Log::error('MP Webhook error: ' . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }
}

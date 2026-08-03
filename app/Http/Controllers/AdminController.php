<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'orders_total'   => Order::count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'customers'      => User::where('role', 'customer')->count(),
            'low_stock'      => Product::where('stock_qty', '<=', 5)->count(),
        ];
        $recent_orders = Order::with('items')->latest()->take(10)->get();
        $low_stock     = Product::where('stock_qty', '<=', 5)->orderBy('stock_qty')->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'low_stock'));
    }

    // ── ORDERS ──

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_email', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['items', 'user']);
        return view('admin.order_detail', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,processing,completed,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', "Pedido {$order->order_number} atualizado.");
    }

    // ── PRODUCTS / STOCK ──

    public function products()
    {
        $products = Product::orderBy('category')->orderBy('name')->get();
        return view('admin.products', compact('products'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate(['stock_qty' => 'required|integer|min:0']);
        $product->update(['stock_qty' => $request->stock_qty]);
        return back()->with('success', "Estoque de \"{$product->name}\" atualizado para {$request->stock_qty} unidades.");
    }

    public function updatePrice(Request $request, Product $product)
    {
        $request->validate(['price' => 'nullable|numeric|min:0']);
        $product->update(['price' => $request->price ?: null]);
        return back()->with('success', "Preço de \"{$product->name}\" atualizado.");
    }

    public function bulkUpdate(Request $request)
    {
        $rows = $request->input('products', []);
        $count = 0;
        foreach ($rows as $id => $values) {
            $product = Product::find((int) $id);
            if (!$product) continue;
            $product->update([
                'price'     => isset($values['price']) && $values['price'] !== '' ? (float) $values['price'] : null,
                'stock_qty' => (int) ($values['stock_qty'] ?? 0),
            ]);
            $count++;
        }
        return back()->with('success', "$count produtos atualizados.");
    }

    public function exportCsv()
    {
        $products = Product::orderBy('category')->orderBy('name')->get();
        $rows = ["sku,name,category,price,stock_qty"];
        foreach ($products as $p) {
            $rows[] = implode(',', [
                $p->sku,
                '"' . str_replace('"', '""', $p->name) . '"',
                '"' . str_replace('"', '""', $p->category) . '"',
                $p->price ?? '',
                $p->stock_qty,
            ]);
        }
        return response(implode("\n", $rows), 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="estoque-fireout.csv"',
        ]);
    }

    public function importCsv(Request $request)
    {
        $request->validate(['csv' => 'required|file|mimes:csv,txt|max:1024']);

        $lines = array_map('str_getcsv', file($request->file('csv')->getRealPath()));
        $header = array_map(fn($h) => strtolower(trim($h)), array_shift($lines));

        $updated = 0;
        foreach ($lines as $line) {
            if (count($line) < 2) continue;
            $row     = array_combine($header, array_pad($line, count($header), ''));
            $product = Product::where('sku', trim($row['sku'] ?? ''))->first();
            if (!$product) continue;

            $patch = [];
            $priceCol = $row['price'] ?? $row['preco'] ?? null;
            $stockCol = $row['stock_qty'] ?? $row['estoque'] ?? null;
            if ($priceCol !== null && $priceCol !== '') $patch['price']     = (float) str_replace(',', '.', $priceCol);
            if ($stockCol !== null && $stockCol !== '') $patch['stock_qty'] = (int) $stockCol;
            if ($patch) { $product->update($patch); $updated++; }
        }
        return back()->with('success', "CSV importado: $updated produtos atualizados.");
    }
}

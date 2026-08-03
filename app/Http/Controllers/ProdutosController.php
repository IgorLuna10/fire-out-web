<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProdutosController extends Controller
{
    public function index()
    {
        $sizeLabels = ['1'=>'1"','15'=>'1½"','2'=>'2"','25'=>'2½"','3'=>'3"','4'=>'4"','6'=>'6"'];

        $abracadeiras = Product::where('sku', 'like', 'abrac-%')
            ->orderBy('sku')
            ->get()
            ->filter(fn($p) => preg_match('/^abrac-[^-]+-\d+$/', $p->sku))
            ->groupBy(function ($p) {
                preg_match('/^abrac-(.+)-\d+$/', $p->sku, $m);
                return $m[1] ?? '';
            })
            ->map(function ($group, $sizeKey) use ($sizeLabels) {
                $kits = $group->map(function ($p) {
                    preg_match('/^abrac-.+-(\d+)$/', $p->sku, $m);
                    return [
                        'qty'             => $m[1] ?? '?',
                        'sku'             => $p->sku,
                        'price'           => $p->price ? (float) $p->price : null,
                        'price_formatted' => $p->price_formatted,
                    ];
                })->sortBy(fn($k) => (int) $k['qty'])->values()->toArray();

                return [
                    'size_key'   => $sizeKey,
                    'size_label' => $sizeLabels[$sizeKey] ?? $sizeKey . '"',
                    'material'   => in_array($sizeKey, ['1', '15']) ? 'Chapa 24' : 'Chapa 18',
                    'kits'       => $kits,
                ];
            })
            ->sortKeys()
            ->values();

        // All DB products keyed by SKU — used to overlay prices in the view
        $dbPrices = Product::all()->keyBy('sku');

        return view('pages.produtos', compact('abracadeiras', 'dbPrices'));
    }

    public function show(string $sku)
    {
        $related = collect(self::catalogue())
            ->except($sku)
            ->map(fn($p, $s) => ['sku' => $s, 'name' => $p['name'], 'category' => $p['category'], 'image' => $p['image'] ?? null, 'price' => $p['variants'][0]['price'] ?? null])
            ->shuffle()
            ->take(5)
            ->values();

        // Abraçadeiras come from DB
        if (preg_match('/^abrac-([^-]+)$/', $sku, $m)) {
            $sizeLabels = ['1'=>'1"','15'=>'1½"','2'=>'2"','25'=>'2½"','3'=>'3"','4'=>'4"','6'=>'6"'];
            $sizeKey    = $m[1];
            $kits = Product::where('sku', 'like', "abrac-{$sizeKey}-%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(sku, "-", -1) AS UNSIGNED)')
                ->get();
            if ($kits->isEmpty()) abort(404);
            $label    = $sizeLabels[$sizeKey] ?? $sizeKey . '"';
            $material = in_array($sizeKey, ['1','15']) ? 'Chapa 24' : 'Chapa 18';
            $abracImages = [
                'img/fire out images/abracadeira-gota.png',
                'img/fire out images/abracadeira-tamanhos.png',
                'img/fire out images/abracadeira-inox.png',
            ];
            $abracVariants = [];
            foreach ($kits->values() as $i => $p) {
                $abracVariants[] = [
                    'label'           => 'Kit ' . last(explode('-', $p->sku)) . ' un.',
                    'price'           => $p->price ? (float)$p->price : null,
                    'price_formatted' => $p->price_formatted,
                    'image'           => $abracImages[0],
                    'specs'           => [
                        'Bitola: '     . $label,
                        'Quantidade: ' . last(explode('-', $p->sku)) . ' unidades',
                        'Material: '   . $material,
                        'Tipo: Gota',
                    ],
                    'wa' => rawurlencode('Olá! Gostaria de um orçamento: Abraçadeira ' . $label . ' Kit ' . last(explode('-', $p->sku)) . ' un.'),
                ];
            }
            $product = [
                'name'     => 'Abraçadeira ' . $label,
                'category' => 'Abraçadeiras',
                'desc'     => 'Abraçadeira Tipo Gota em ' . $material . '. Para amarração e ancoragem de tubulações hidráulicas e elétricas em sistemas de combate a incêndio.',
                'image'    => $abracImages[0],
                'images'   => $abracImages,
                'variants' => $abracVariants,
            ];
            return view('pages.produto', compact('product', 'sku', 'related'));
        }

        $catalogue = self::catalogue();
        if (!isset($catalogue[$sku])) abort(404);
        $product = $catalogue[$sku];

        // Overlay prices from DB so admin changes are reflected
        $dbProducts = Product::whereIn('sku',
            array_filter(array_column($product['variants'], 'db_sku'))
        )->get()->keyBy('sku');

        foreach ($product['variants'] as &$v) {
            $dbSku = $v['db_sku'] ?? null;
            if ($dbSku && $dbProducts->has($dbSku)) {
                $p = $dbProducts[$dbSku];
                $v['price']           = $p->price ? (float) $p->price : null;
                $v['price_formatted'] = $p->price_formatted;
            }
        }
        unset($v);

        // Set top-level price from first variant
        $product['price'] = $product['variants'][0]['price'] ?? null;

        return view('pages.produto', compact('product', 'sku', 'related'));
    }

    // db_sku maps each variant to the corresponding products table row
    private static function catalogue(): array
    {
        return [
            'mangueira-t1-15' => [
                'name' => 'Mangueira Tipo 1 — 1½"', 'category' => 'Mangueiras',
                'desc' => 'Para hidrantes internos residenciais. Revestimento externo 100% poliéster, tubo interno borracha sintética, conexões Storz em latão forjado. Dupla certificação INMETRO e UL.',
                'image' => 'img/fire out images/Mangueira 1.1:2.jpg',
                'variants' => [['db_sku'=>'mangueira-t1-15','label'=>null,'price'=>280.00,'image'=>'img/fire out images/Mangueira 1.1:2.jpg',
                    'specs'=>['Tipo: 1 — Edifícios Residenciais','Bitola: 1½" (38mm)','Comprimento: 15 metros','Pressão máx.: 980 kPa','Normas: NBR 14349 e NBR 11861','Certificação: INMETRO + UL'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Mangueira Tipo 1 1½".')]]],
            'mangueira-t2-15' => [
                'name' => 'Mangueira Tipo 2 — 1½"', 'category' => 'Mangueiras',
                'desc' => 'Para uso comercial, industrial e Corpo de Bombeiros. Maior resistência mecânica que o Tipo 1.',
                'image' => 'img/fire out images/Mangueira 1.1:2.jpg',
                'variants' => [['db_sku'=>'mangueira-t2-15','label'=>null,'price'=>340.00,'image'=>'img/fire out images/Mangueira 1.1:2.jpg',
                    'specs'=>['Tipo: 2 — Comercial, Industrial e Bombeiros','Bitola: 1½" (38mm)','Comprimento: 15 metros','Pressão máx.: 1370 kPa','Normas: NBR 14349 e NBR 11861','Certificação: INMETRO + UL'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Mangueira Tipo 2 1½".')]]],
            'mangueira-t2-25' => [
                'name' => 'Mangueira Tipo 2 — 2½"', 'category' => 'Mangueiras',
                'desc' => 'Para indústrias e áreas de alto risco. Maior vazão e resistência para combate profissional.',
                'image' => 'img/fire out images/mangueira 2.1:2.jpg',
                'variants' => [['db_sku'=>'mangueira-t2-25','label'=>null,'price'=>540.00,'image'=>'img/fire out images/mangueira 2.1:2.jpg',
                    'specs'=>['Tipo: 2 — Comercial, Industrial e Bombeiros','Bitola: 2½" (65mm)','Comprimento: 15 metros','Pressão máx.: 1370 kPa','Normas: NBR 14349 e NBR 11861','Certificação: INMETRO + UL'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Mangueira Tipo 2 2½".')]]],
            'esguicho' => [
                'name' => 'Esguicho Regulável Storz', 'category' => 'Sistema de Hidrantes',
                'desc' => 'Latão forjado de alta performance. Controle operacional em 3 posições: fechado, jato sólido e cortina de neblina.',
                'image' => 'img/fire out images/esguicho.jpg',
                'variants' => [
                    ['db_sku'=>'esguicho-15','label'=>'1½"','price'=>140.00,'image'=>'img/fire out images/esguicho.jpg',
                        'specs'=>['Conexão: 1½" Storz','Jato sólido e cortina de neblina','Material: Latão forjado'],
                        'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Esguicho 1½".')],
                    ['db_sku'=>'esguicho-25','label'=>'2½"','price'=>199.00,'image'=>'img/fire out images/esguicho.jpg',
                        'specs'=>['Conexão: 2½" Storz','Maior vazão','Material: Latão forjado'],
                        'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Esguicho 2½".')],
                ]],
            'adaptador' => [
                'name' => 'Adaptador Storz', 'category' => 'Sistema de Hidrantes',
                'desc' => 'Engate rápido em latão de alta durabilidade. Acopla o registro globo do hidrante à mangueira de combate.',
                'image' => 'img/fire out images/adaptador 2.1:2.jpg',
                'variants' => [
                    ['db_sku'=>'adaptador-2515','label'=>'2½" × 1½"','price'=>110.00,'image'=>'img/fire out images/adaptador 2.1:2.jpg',
                        'specs'=>['Entrada: 2½" Rosca','Saída: 1½" Storz','Material: Latão'],
                        'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Adaptador 2½" × 1½".')],
                    ['db_sku'=>'adaptador-25','label'=>'2½"','price'=>180.00,'image'=>'img/fire out images/adaptador 2.1:2.jpg',
                        'specs'=>['Conexão: 2½"','Material: Latão'],
                        'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Adaptador 2½".')],
                ]],
            'moldura' => [
                'name' => 'Moldura para Caixa de Incêndio', 'category' => 'Acessórios',
                'desc' => 'Caixa em chapa 18 galvanizada para abrigo de mangueiras. Acabamento vermelho padrão Rio com fecho porta-cadeado.',
                'image' => 'img/fire out images/moldura-caixa-1.jpg',
                'variants' => [
                    ['db_sku'=>'moldura-6040','label'=>'60×40 cm','price'=>200.00,'image'=>'img/fire out images/moldura-caixa-1.jpg',
                        'specs'=>['60×40 cm','Chapa 18 galvanizada','Vermelho padrão Rio','Fecho porta-cadeado'],
                        'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Moldura 60×40 cm.')],
                    ['db_sku'=>'moldura-7050','label'=>'70×50 cm','price'=>250.00,'image'=>'img/fire out images/moldura-caixa-2.jpg',
                        'specs'=>['70×50 cm','Chapa 18 galvanizada','Vermelho padrão Rio','Fecho porta-cadeado'],
                        'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Moldura 70×50 cm.')],
                ]],
            'antipanico' => [
                'name' => 'Barra Antipânico', 'category' => 'Portas e Proteção',
                'desc' => 'Garante o destravamento imediato da porta sob qualquer pressão exercida pelo lado de fuga. Obrigatória pela NBR 11785.',
                'image' => 'img/fire out images/antipanico.jpg',
                'variants' => [['db_sku'=>'antipanico','label'=>null,'price'=>null,'image'=>'img/fire out images/antipanico.jpg',
                    'specs'=>['Norma: NBR 11785','Vertical ou horizontal','Hospitais, shoppings, indústrias','Simples pressão pelo lado de fuga'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Barra Antipânico.')]]],
            'portas-duplas' => [
                'name' => 'Portas Duplas / Fora de Padrão', 'category' => 'Portas e Proteção',
                'desc' => 'Portas corta-fogo duplas e medidas especiais. Fabricação própria Fire Out sob medida. Prazo de 5 dias úteis.',
                'image' => 'img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp',
                'variants' => [['db_sku'=>'portas-duplas','label'=>null,'price'=>null,'image'=>'img/fire out images/14 porta-corta-fogo-p90-abnt-11742.webp',
                    'specs'=>['Medidas especiais sob pedido','Fabricação própria','ABNT NBR 11742','Prazo: 5 dias úteis'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Portas Duplas/Fora de Padrão.')]]],
            'canopla' => [
                'name' => 'Canopla em Aço Inox ½"', 'category' => 'Suportes e Acessórios',
                'desc' => 'Acabamento estético e funcional para sprinklers em forros de gesso. Máxima resistência à corrosão — ideal para maresia.',
                'image' => 'img/fire out images/20 canopla acabamento-para-sprinklers-12-200-unidades.webp',
                'variants' => [['db_sku'=>'canopla','label'=>null,'price'=>4.00,'image'=>'img/fire out images/20 canopla acabamento-para-sprinklers-12-200-unidades.webp',
                    'specs'=>['Aço inox · Ø 63mm','½" SPK','Anti-corrosão'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Canopla Aço Inox ½".')]]],
            'tripe' => [
                'name' => 'Tripé Reforçado com Haste', 'category' => 'Suportes e Acessórios',
                'desc' => 'Suporte de solo para extintores onde não há parede disponível. Indicado para extintores PQS.',
                'image' => 'img/fire out images/tripe-extintor.png',
                'variants' => [['db_sku'=>'tripe','label'=>null,'price'=>50.00,'image'=>'img/fire out images/tripe-extintor.png',
                    'specs'=>['Chapa 18','Base 19 cm','Para extintores PQS','Sem placa de sinalização'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Tripé Reforçado.')]]],
            'extintor-abc' => [
                'name' => 'Extintor ABC — 6 kg', 'category' => 'Extintores',
                'desc' => 'Classes A (sólidos), B (líquidos/gases) e C (elétrico). Suporte de parede incluso. Certificado INMETRO.',
                'image' => 'img/fire out images/Extintor PQS.jpg',
                'variants' => [['db_sku'=>'extintor-abc','label'=>null,'price'=>250.00,'image'=>'img/fire out images/Extintor PQS.jpg',
                    'specs'=>['6 kg PQS ABC','Descarga ~15 seg.','NBR 15808 · INMETRO','Suporte de parede incluso'],
                    'wa'=>rawurlencode('Olá! Gostaria de um orçamento: Extintor ABC 6 kg.')]]],
        ];
    }
}

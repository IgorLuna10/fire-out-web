<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Mangueiras
            ['sku' => 'mangueira-t1-15', 'name' => 'Mangueira Tipo 1 — 1½"',    'category' => 'Mangueiras', 'price' => 280.00, 'stock_qty' => 0],
            ['sku' => 'mangueira-t2-15', 'name' => 'Mangueira Tipo 2 — 1½"',    'category' => 'Mangueiras', 'price' => 340.00, 'stock_qty' => 0],
            ['sku' => 'mangueira-t2-25', 'name' => 'Mangueira Tipo 2 — 2½"',    'category' => 'Mangueiras', 'price' => 540.00, 'stock_qty' => 0],
            ['sku' => 'esguicho-15',     'name' => 'Esguicho Regulável 1½"',     'category' => 'Mangueiras', 'price' => 140.00, 'stock_qty' => 0],
            ['sku' => 'esguicho-25',     'name' => 'Esguicho Regulável 2½"',     'category' => 'Mangueiras', 'price' => 199.00, 'stock_qty' => 0],
            ['sku' => 'adaptador-2515',  'name' => 'Adaptador 2½" × 1½"',        'category' => 'Mangueiras', 'price' => 110.00, 'stock_qty' => 0],
            ['sku' => 'adaptador-25',    'name' => 'Adaptador 2½"',              'category' => 'Mangueiras', 'price' => 180.00, 'stock_qty' => 0],
            ['sku' => 'extintor-abc',    'name' => 'Extintor ABC — 6 kg',        'category' => 'Mangueiras', 'price' => 250.00, 'stock_qty' => 0],
            // Portas e Molduras
            ['sku' => 'porta-cf',        'name' => 'Porta Corta-Fogo P-90',      'category' => 'Portas',     'price' => null,   'stock_qty' => 0],
            ['sku' => 'moldura-7050',    'name' => 'Moldura 70×50 cm',           'category' => 'Portas',     'price' => 250.00, 'stock_qty' => 0],
            ['sku' => 'moldura-6040',    'name' => 'Moldura 60×40 cm',           'category' => 'Portas',     'price' => 200.00, 'stock_qty' => 0],
            ['sku' => 'antipanico',      'name' => 'Barra Antipânico',           'category' => 'Portas',     'price' => null,   'stock_qty' => 0],
            ['sku' => 'portas-duplas',   'name' => 'Portas Duplas / Fora de Padrão', 'category' => 'Portas', 'price' => null,   'stock_qty' => 0],
            // Abraçadeiras — por tamanho × quantidade
            ['sku' => 'abrac-1-10',   'name' => 'Abraçadeira 1" — Kit 10 un.',   'category' => 'Abraçadeiras', 'price' =>   8.00, 'stock_qty' => 0],
            ['sku' => 'abrac-1-50',   'name' => 'Abraçadeira 1" — Kit 50 un.',   'category' => 'Abraçadeiras', 'price' =>  40.00, 'stock_qty' => 0],
            ['sku' => 'abrac-1-100',  'name' => 'Abraçadeira 1" — Kit 100 un.',  'category' => 'Abraçadeiras', 'price' =>  80.00, 'stock_qty' => 0],
            ['sku' => 'abrac-15-10',  'name' => 'Abraçadeira 1½" — Kit 10 un.',  'category' => 'Abraçadeiras', 'price' =>  14.00, 'stock_qty' => 0],
            ['sku' => 'abrac-15-50',  'name' => 'Abraçadeira 1½" — Kit 50 un.',  'category' => 'Abraçadeiras', 'price' =>  70.00, 'stock_qty' => 0],
            ['sku' => 'abrac-15-100', 'name' => 'Abraçadeira 1½" — Kit 100 un.', 'category' => 'Abraçadeiras', 'price' => 140.00, 'stock_qty' => 0],
            ['sku' => 'abrac-2-10',   'name' => 'Abraçadeira 2" — Kit 10 un.',   'category' => 'Abraçadeiras', 'price' =>  15.00, 'stock_qty' => 0],
            ['sku' => 'abrac-2-50',   'name' => 'Abraçadeira 2" — Kit 50 un.',   'category' => 'Abraçadeiras', 'price' =>  75.00, 'stock_qty' => 0],
            ['sku' => 'abrac-2-100',  'name' => 'Abraçadeira 2" — Kit 100 un.',  'category' => 'Abraçadeiras', 'price' => 150.00, 'stock_qty' => 0],
            ['sku' => 'abrac-25-10',  'name' => 'Abraçadeira 2½" — Kit 10 un.',  'category' => 'Abraçadeiras', 'price' =>  23.00, 'stock_qty' => 0],
            ['sku' => 'abrac-25-50',  'name' => 'Abraçadeira 2½" — Kit 50 un.',  'category' => 'Abraçadeiras', 'price' => 115.00, 'stock_qty' => 0],
            ['sku' => 'abrac-25-100', 'name' => 'Abraçadeira 2½" — Kit 100 un.', 'category' => 'Abraçadeiras', 'price' => 230.00, 'stock_qty' => 0],
            ['sku' => 'abrac-3-10',   'name' => 'Abraçadeira 3" — Kit 10 un.',   'category' => 'Abraçadeiras', 'price' =>  27.00, 'stock_qty' => 0],
            ['sku' => 'abrac-3-50',   'name' => 'Abraçadeira 3" — Kit 50 un.',   'category' => 'Abraçadeiras', 'price' => 135.00, 'stock_qty' => 0],
            ['sku' => 'abrac-3-100',  'name' => 'Abraçadeira 3" — Kit 100 un.',  'category' => 'Abraçadeiras', 'price' => 270.00, 'stock_qty' => 0],
            ['sku' => 'abrac-4-10',   'name' => 'Abraçadeira 4" — Kit 10 un.',   'category' => 'Abraçadeiras', 'price' =>  28.00, 'stock_qty' => 0],
            ['sku' => 'abrac-4-50',   'name' => 'Abraçadeira 4" — Kit 50 un.',   'category' => 'Abraçadeiras', 'price' => 140.00, 'stock_qty' => 0],
            ['sku' => 'abrac-4-100',  'name' => 'Abraçadeira 4" — Kit 100 un.',  'category' => 'Abraçadeiras', 'price' => 280.00, 'stock_qty' => 0],
            ['sku' => 'abrac-6-10',   'name' => 'Abraçadeira 6" — Kit 10 un.',   'category' => 'Abraçadeiras', 'price' =>  54.00, 'stock_qty' => 0],
            ['sku' => 'abrac-6-50',   'name' => 'Abraçadeira 6" — Kit 50 un.',   'category' => 'Abraçadeiras', 'price' => 270.00, 'stock_qty' => 0],
            ['sku' => 'abrac-6-100',  'name' => 'Abraçadeira 6" — Kit 100 un.',  'category' => 'Abraçadeiras', 'price' => 540.00, 'stock_qty' => 0],
            // Suportes
            ['sku' => 'canopla',         'name' => 'Canopla em Aço Inox ½"',    'category' => 'Suportes',   'price' => 4.00,  'stock_qty' => 0],
            ['sku' => 'tripe',           'name' => 'Tripé Reforçado com Haste', 'category' => 'Suportes',   'price' => 50.00, 'stock_qty' => 0],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(['sku' => $data['sku']], $data);
        }
    }
}

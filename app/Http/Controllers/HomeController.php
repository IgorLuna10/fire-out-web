<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $clientes = [
            ['nome' => 'Hospital São Gonçalo', 'logo' => 'hospital.webp'],
            ['nome' => 'Hospital Icaraí',      'logo' => 'hospital2.webp'],
            ['nome' => 'Amoedo',               'logo' => 'amoedo-logo.webp'],
            ['nome' => 'Distac Volkswagen',    'logo' => 'distac-logo.webp'],
            ['nome' => 'Unimed',               'logo' => 'unimed-logo.webp'],
            ['nome' => 'Frescatto',            'logo' => 'frescatto-logo.webp'],
            ['nome' => 'Fundação Atauvo de Paiva', 'logo' => 'FAP-logo.webp'],
            ['nome' => 'Viação Redentor',      'logo' => 'redentor-logo.webp'],
        ];

        return view('pages.home', compact('clientes'));
    }
}

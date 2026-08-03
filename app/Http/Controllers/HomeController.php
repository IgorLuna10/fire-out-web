<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $clientes = [
            ['nome' => 'Hospital São Gonçalo', 'logo' => 'hospital.png'],
            ['nome' => 'Hospital Icaraí',      'logo' => 'hospital2.png'],
            ['nome' => 'Amoedo',               'logo' => 'amoedo-logo.png'],
            ['nome' => 'Distac Volkswagen',    'logo' => 'distac-logo.png'],
            ['nome' => 'Unimed',               'logo' => 'unimed-logo.png'],
            ['nome' => 'Frescatto',            'logo' => 'frescatto-logo.png'],
            ['nome' => 'Fundação Atauvo de Paiva', 'logo' => 'FAP-logo.png'],
            ['nome' => 'Viação Redentor',      'logo' => 'redentor-logo.png'],
        ];

        return view('pages.home', compact('clientes'));
    }
}

<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Sileh',

        ];

        echo view('pages/v_home', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'About | Sileh',

        ];

        echo view('pages/v_about', $data);
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact | Sileh',
            'alamat' => [
                [
                    'tipe'  => 'kos',
                    'alamat' => 'Jl. Palagan',
                    'kota'  => 'Yogyakarta'
                ],
                [
                    'tipe'  => 'rumah',
                    'alamat' => 'Jl. Magelang',
                    'kota'  => 'Magelang '
                ]
            ]
        ];

        echo view('pages/v_contact', $data);
    }
}

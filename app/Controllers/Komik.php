<?php

namespace App\Controllers;

use App\Models\M_komik;

class Komik extends BaseController
{
    protected $M_komik;

    public function __construct()
    {
        $this->M_komik = new M_komik;
    }

    public function index()
    {
        $data = [
            'title' => 'Komik | Sileh',
            'komik' => $this->M_komik->getKomik()

        ];

        return view('komik/v_komik', $data);
    }

    public function detail($slug)
    {
        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->M_komik->getKomik($slug)
        ];

        // jika judul komik tidak ada (masih error)

        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik' . $slug . 'tidak ditemukan');
        }

        return view('Komik/v_detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form tambah data komik',

        ];

        return view('komik/v_create', $data);
    }

    public function save()
    {
        // dd($this->request->getVar());
        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->M_komik->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashdata('pesan', 'data berhasil ditambahkan');
        return redirect()->to('/komik');
    }
}

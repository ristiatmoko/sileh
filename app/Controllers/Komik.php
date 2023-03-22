<?php

namespace App\Controllers;

use App\Models\M_komik;

class Komik extends BaseController
{
    protected $M_komik;
    protected $helpers = ['form'];

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
        // session();
        $data = [
            'title' => 'Form tambah data komik',
            // 'validation' => \Config\Services::validation()
        ];

        return view('komik/v_create', $data);
    }

    public function save()
    {
        // dd($this->request->getVar());
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->to('/komik/create')->withInput();
        }


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

    public function delete($id_komik)
    {
        $this->M_komik->delete($id_komik);
        session()->setFlashdata('pesan', 'data berhasil dihapus');

        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form ubah data komik',
            'komik' => $this->M_komik->getKomik($slug)
        ];

        return view('komik/v_edit', $data);
    }

    public function update($id_komik)
    {
        // cek judul
        $komikLama = $this->M_komik->getKomik($this->request->getVar('slug'));
        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }

        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus diisi.',
                    'is_unique' => '{field} komik sudah terdaftar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }


        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->M_komik->save([
            'id_komik' => $id_komik,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $this->request->getVar('sampul')
        ]);

        session()->setFlashdata('pesan', 'data berhasil diubah');
        return redirect()->to('/komik');
    }
}

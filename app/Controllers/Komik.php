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
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'pilih gambar terlebih dahulu',
                    'max_size' => 'ukuran gambar terlalu besar',
                    'is_image' => 'yang anda pilih bukan gambar',
                    'mime_in' => 'yang anda pilih bukan gambar',
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->to('/komik/create')->withInput();
        }

        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');

        // apakah tidak ada gambar yg diupload
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.jpg';
        } else {
            // generate nama file random
            $namaSampul = $fileSampul->getRandomName();

            // pindahkan file gambar
            $fileSampul->move('img', $namaSampul);
        }


        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->M_komik->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'data berhasil ditambahkan');
        return redirect()->to('/komik');
    }

    public function delete($id_komik)
    {
        // cari gambar berdasarkan id
        $komik = $this->M_komik->find($id_komik);

        // cek jika file gambarnya default.jpg
        if ($komik['sampul'] != 'default.jpg') {
            // hapus gambar
            unlink('img/' . $komik['sampul']);
        }

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
            ],
            'sampul' => [
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'pilih gambar terlebih dahulu',
                    'max_size' => 'ukuran gambar terlalu besar',
                    'is_image' => 'yang anda pilih bukan gambar',
                    'mime_in' => 'yang anda pilih bukan gambar',
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        // cek apakah gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate nama file gambar random
            $namaSampul = $fileSampul->getRandomName();
            // pndahkan gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file yg lama
            if ($this->request->getVar('sampulLama') != 'default.jpg') {
                unlink('img/' . $this->request->getVar('sampulLama'));
            }
        }


        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->M_komik->save([
            'id_komik' => $id_komik,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'data berhasil diubah');
        return redirect()->to('/komik');
    }
}

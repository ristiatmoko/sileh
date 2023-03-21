<?php

namespace App\Models;

use CodeIgniter\Model;

class M_komik extends Model
{
    protected $table = 'komik';
    protected $primaryKey = 'id_komik';
    protected $useTimestamps = 'true';
    protected $allowedFields = ['judul', 'slug', 'penulis', 'penerbit', 'sampul'];


    public function getKomik($slug = false)
    {
        if ($slug == false) {
            return $this->findAll();
        }

        return $this->where(['slug' => $slug])->first();
    }
}

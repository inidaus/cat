<?php
namespace App\Models;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table = 'soal';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e',
        'kunci_jawaban', 'bobot', 'kategori_id', 'pembimbing_id'
    ];
}

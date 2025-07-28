<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table = 'ujian';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul', 'kategori_id', 'pembimbing_id', 'jumlah_soal', 'waktu_menit',
        'toleransi_menit', 'acak_soal', 'passing_grade', 'mulai', 'selesai', 'status', 'kategori_data'
    ];
    protected $useTimestamps = true;
}

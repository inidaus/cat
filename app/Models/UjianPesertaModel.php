<?php
namespace App\Models;
use CodeIgniter\Model;

class UjianPesertaModel extends Model
{
    protected $table = 'ujian_peserta';
    protected $allowedFields = ['ujian_id', 'peserta_id', 'status', 'nilai', 'mulai', 'selesai'];
}

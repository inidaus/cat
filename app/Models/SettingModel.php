<?php
namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'setting_app';
    protected $primaryKey = 'id';
    protected $allowedFields = ['timezone'];
    public $timestamps = false;
}
/*
class KategoriModel extends Model
{
    protected $table = 'kategori_soal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_kategori'];
}
*/
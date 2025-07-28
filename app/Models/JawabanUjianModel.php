<?php

namespace App\Models;

use CodeIgniter\Model;

class JawabanUjianModel extends Model
{
    protected $table            = 'jawaban_ujian';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['ujian_id', 'peserta_id', 'soal_id', 'jawaban', 'updated_at'];
    protected $useTimestamps    = true;
    protected $createdField     = '';
    protected $updatedField     = 'updated_at';
    protected $useSoftDeletes   = false;
}

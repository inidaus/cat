<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalisaSoalModel extends Model
{
    protected $table = 'jawaban_ujian';
    protected $primaryKey = 'id';

    /**
     * Mendapatkan analisa untuk semua soal
     */
    public function getAnalisaSoal($soalIds = [])
    {
        $builder = $this->db->table('jawaban_ujian ju')
            ->select('ju.soal_id, 
                     COUNT(ju.id) as jumlah_dipakai,
                     SUM(CASE WHEN ju.jawaban = s.kunci_jawaban THEN 1 ELSE 0 END) as jumlah_benar,
                     SUM(CASE WHEN ju.jawaban != s.kunci_jawaban AND ju.jawaban IS NOT NULL THEN 1 ELSE 0 END) as jumlah_salah')
            ->join('soal s', 's.id = ju.soal_id')
            ->groupBy('ju.soal_id');

        if (!empty($soalIds)) {
            $builder->whereIn('ju.soal_id', $soalIds);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Mendapatkan analisa untuk satu soal
     */
    public function getAnalisaSoalById($soalId)
    {
        return $this->db->table('jawaban_ujian ju')
            ->select('ju.soal_id, 
                     COUNT(ju.id) as jumlah_dipakai,
                     SUM(CASE WHEN ju.jawaban = s.kunci_jawaban THEN 1 ELSE 0 END) as jumlah_benar,
                     SUM(CASE WHEN ju.jawaban != s.kunci_jawaban AND ju.jawaban IS NOT NULL THEN 1 ELSE 0 END) as jumlah_salah')
            ->join('soal s', 's.id = ju.soal_id')
            ->where('ju.soal_id', $soalId)
            ->groupBy('ju.soal_id')
            ->get()
            ->getRowArray();
    }
}

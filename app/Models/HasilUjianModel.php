<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table = 'ujian_peserta';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ujian_id', 
        'peserta_id', 
        'status', 
        'nilai', 
        'mulai', 
        'selesai',
        'jawaban',
        'skor_benar',
        'skor_salah',
        'total_soal'
    ];
    
    protected $useTimestamps = false;
    
    public function getHasilByPeserta($pesertaId)
    {
        // PERBAIKAN: Gunakan query tanpa JOIN kategori_soal untuk menghindari masalah
        $hasil = $this->select('ujian_peserta.*, ujian.judul, ujian.waktu_menit as durasi, ujian.passing_grade, ujian.jumlah_soal as total_soal, ujian.kategori_data, ujian.kategori_id')
                    ->join('ujian', 'ujian.id = ujian_peserta.ujian_id', 'left')
                    ->where('ujian_peserta.peserta_id', $pesertaId)
                    ->where('ujian_peserta.status', 'selesai')
                    ->orderBy('ujian_peserta.selesai', 'DESC')
                    ->findAll();

        // Tambahkan nama_kategori secara manual
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($hasil as &$item) {
            if (!empty($item['kategori_id'])) {
                $kategori = $kategoriModel->find($item['kategori_id']);
                $item['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Kategori Tidak Ditemukan';
            } else {
                $item['nama_kategori'] = 'Kategori Tidak Ditemukan';
            }
        }

        // Process kategori_data untuk multiple mata pelajaran
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($hasil as &$h) {
            if (!empty($h['kategori_data'])) {
                $kategoriData = json_decode($h['kategori_data'], true);
                if ($kategoriData && is_array($kategoriData)) {
                    $namaKategoriList = [];

                    foreach ($kategoriData as $kd) {
                        $kategori = $kategoriModel->find($kd['kategori_id']);
                        if ($kategori) {
                            $namaKategoriList[] = $kategori['nama_kategori'];
                        }
                    }

                    $h['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                    // Keep individual passing grades for reference, but use ujian.passing_grade as total
                }
            }
        }

        return $hasil;
    }
    
    public function getDetailHasil($ujianId, $pesertaId)
    {
        $detail = $this->select('ujian_peserta.*, ujian.judul, ujian.waktu_menit as durasi, ujian.passing_grade, ujian.jumlah_soal, ujian.kategori_data, kategori_soal.nama_kategori')
                    ->join('ujian', 'ujian.id = ujian_peserta.ujian_id')
                    ->join('kategori_soal', 'kategori_soal.id = ujian.kategori_id')
                    ->where('ujian_peserta.ujian_id', $ujianId)
                    ->where('ujian_peserta.peserta_id', $pesertaId)
                    ->first();

        if ($detail && !empty($detail['kategori_data'])) {
            $kategoriData = json_decode($detail['kategori_data'], true);
            if ($kategoriData && is_array($kategoriData)) {
                $kategoriModel = new \App\Models\KategoriModel();
                $namaKategoriList = [];

                foreach ($kategoriData as $kd) {
                    $kategori = $kategoriModel->find($kd['kategori_id']);
                    if ($kategori) {
                        $namaKategoriList[] = $kategori['nama_kategori'];
                    }
                }

                $detail['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                $detail['kategori_data_parsed'] = $kategoriData;
                // Use ujian.passing_grade as the total passing grade
            }
        }

        return $detail;
    }
    
    public function getStatistikPeserta($pesertaId)
    {
        // Get hasil dengan passing grade dari ujian
        $hasil = $this->select('ujian_peserta.*, ujian.passing_grade')
                      ->join('ujian', 'ujian.id = ujian_peserta.ujian_id')
                      ->where('ujian_peserta.peserta_id', $pesertaId)
                      ->where('ujian_peserta.status', 'selesai')
                      ->findAll();

        $total_ujian = count($hasil);
        $total_lulus = 0;
        $rata_rata = 0;
        $nilai_tertinggi = 0;
        $nilai_terendah = 100;

        if ($total_ujian > 0) {
            $total_nilai = 0;
            foreach ($hasil as $h) {
                $nilai = $h['nilai'] ?? 0;
                $passingGrade = $h['passing_grade'] ?? 70;
                $total_nilai += $nilai;

                if ($nilai >= $passingGrade) {
                    $total_lulus++;
                }

                if ($nilai > $nilai_tertinggi) {
                    $nilai_tertinggi = $nilai;
                }

                if ($nilai < $nilai_terendah) {
                    $nilai_terendah = $nilai;
                }
            }

            $rata_rata = round($total_nilai / $total_ujian, 2);
        }

        return [
            'total_ujian' => $total_ujian,
            'total_lulus' => $total_lulus,
            'total_tidak_lulus' => $total_ujian - $total_lulus,
            'rata_rata' => $rata_rata,
            'nilai_tertinggi' => $nilai_tertinggi,
            'nilai_terendah' => $total_ujian > 0 ? $nilai_terendah : 0,
            'persentase_lulus' => $total_ujian > 0 ? round(($total_lulus / $total_ujian) * 100, 2) : 0
        ];
    }

    public function hitungSkorUjian($ujianId, $pesertaId)
    {
        $db = \Config\Database::connect();

        // Get jawaban peserta
        $jawaban = $db->table('jawaban_ujian')
                     ->where('ujian_id', $ujianId)
                     ->where('peserta_id', $pesertaId)
                     ->get()
                     ->getResultArray();

        if (empty($jawaban)) {
            return [
                'total_soal' => 0,
                'skor_benar' => 0,
                'skor_salah' => 0,
                'tidak_dijawab' => 0
            ];
        }

        $totalSoal = count($jawaban);
        $skorBenar = 0;
        $skorSalah = 0;
        $tidakDijawab = 0;

        foreach ($jawaban as $j) {
            // Get kunci jawaban dari soal
            $soal = $db->table('soal')
                      ->where('id', $j['soal_id'])
                      ->get()
                      ->getRowArray();

            if (!$soal) continue;

            if (empty($j['jawaban'])) {
                $tidakDijawab++;
            } elseif ($j['jawaban'] === $soal['kunci_jawaban']) {
                $skorBenar++;
            } else {
                $skorSalah++;
            }
        }

        return [
            'total_soal' => $totalSoal,
            'skor_benar' => $skorBenar,
            'skor_salah' => $skorSalah,
            'tidak_dijawab' => $tidakDijawab
        ];
    }
}

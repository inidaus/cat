<?php

namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\HasilUjianModel;
use App\Models\UjianModel;

class Hasil extends BaseController
{
    protected $hasilUjianModel;
    protected $ujianModel;

    public function __construct()
    {
        $this->hasilUjianModel = new HasilUjianModel();
        $this->ujianModel = new UjianModel();
    }

    public function index()
    {
        $userId = session('id');
        
        // Get hasil ujian peserta
        $hasil_ujian = $this->hasilUjianModel->getHasilByPeserta($userId);

        // Hitung rata-rata pencapaian untuk setiap hasil ujian
        foreach ($hasil_ujian as &$hasil) {
            $hasil['rata_rata_pencapaian'] = $this->hitungRataRataPencapaian($hasil);

            // PERBAIKAN: Tentukan kelulusan berdasarkan sistem yang benar
            $hasil['is_lulus'] = $this->tentukanKelulusan($hasil);

            // Debug log
            log_message('debug', "Ujian ID: {$hasil['ujian_id']}, Rata-rata: {$hasil['rata_rata_pencapaian']}, Passing Grade: {$hasil['passing_grade']}, Is Lulus: " . ($hasil['is_lulus'] ? 'true' : 'false'));
        }

        // Hitung statistik berdasarkan rata-rata pencapaian
        $statistik = $this->hitungStatistikPencapaian($hasil_ujian);

        $data = [
            'hasil_ujian' => $hasil_ujian,
            'statistik' => $statistik
        ];

        return view('peserta/hasil/index', $data);
    }
    
    public function detail($ujianId)
    {
        $userId = session('id');

        // Get detail hasil ujian
        $detail = $this->hasilUjianModel->getDetailHasil($ujianId, $userId);

        if (!$detail) {
            return redirect()->to('peserta/hasil')->with('error', 'Hasil ujian tidak ditemukan.');
        }

        // Hitung skor yang benar
        $skor = $this->hasilUjianModel->hitungSkorUjian($ujianId, $userId);

        // Merge skor ke detail
        $detail['total_soal'] = $skor['total_soal'] ?: ($detail['jumlah_soal'] ?? 0);
        $detail['skor_benar'] = $skor['skor_benar'];
        $detail['skor_salah'] = $skor['skor_salah'];
        $detail['tidak_dijawab'] = $skor['tidak_dijawab'];

        // Hitung hasil per mata pelajaran jika ada kategori_data
        $hasilPerMataPelajaran = [];
        if (!empty($detail['kategori_data_parsed'])) {
            $hasilPerMataPelajaran = $this->hitungHasilPerMataPelajaran($ujianId, $userId, $detail['kategori_data_parsed']);
        }

        $detail['hasil_per_mata_pelajaran'] = $hasilPerMataPelajaran;

        // PERBAIKAN: Hitung rata-rata pencapaian dan status kelulusan yang benar
        $detail['rata_rata_pencapaian'] = $this->hitungRataRataPencapaian($detail);
        $detail['is_lulus'] = $this->tentukanKelulusan($detail);

        // Parse jawaban jika ada
        $jawaban = [];
        if (!empty($detail['jawaban'])) {
            $jawaban = json_decode($detail['jawaban'], true) ?? [];
        }

        $data = [
            'detail' => $detail,
            'jawaban' => $jawaban
        ];

        return view('peserta/hasil/detail', $data);
    }
    
    public function sertifikat($ujianId)
    {
        $userId = session('id');
        
        // Get detail hasil ujian
        $detail = $this->hasilUjianModel->getDetailHasil($ujianId, $userId);
        
        if (!$detail || $detail['nilai'] < ($detail['passing_grade'] ?? 70)) {
            return redirect()->to('peserta/hasil')->with('error', 'Sertifikat hanya tersedia untuk ujian yang lulus.');
        }
        
        $data = [
            'detail' => $detail,
            'peserta' => [
                'nama' => session('nama_lengkap'),
                'nim' => session('username')
            ]
        ];
        
        return view('peserta/hasil/sertifikat', $data);
    }

    private function hitungHasilPerMataPelajaran($ujianId, $pesertaId, $kategoriDataParsed)
    {
        $db = \Config\Database::connect();
        $kategoriModel = new \App\Models\KategoriModel();
        $soalModel = new \App\Models\SoalModel();

        $hasil = [];

        foreach ($kategoriDataParsed as $kd) {
            $kategoriId = $kd['kategori_id'];
            $jumlahSoal = $kd['jumlah_soal'];
            $passingGrade = $kd['passing_grade'];

            // Get nama kategori
            $kategori = $kategoriModel->find($kategoriId);
            $namaKategori = $kategori ? $kategori['nama_kategori'] : 'Unknown';

            // Get soal untuk kategori ini
            $soalList = $soalModel->where('kategori_id', $kategoriId)->findAll($jumlahSoal);
            $soalIds = array_column($soalList, 'id');

            if (empty($soalIds)) {
                $hasil[] = [
                    'nama_kategori' => $namaKategori,
                    'jumlah_soal' => $jumlahSoal,
                    'benar' => 0,
                    'salah' => 0,
                    'tidak_dijawab' => $jumlahSoal,
                    'grade' => 0,
                    'passing_grade' => $passingGrade,
                    'status' => 'TIDAK LULUS'
                ];
                continue;
            }

            // Get jawaban untuk soal-soal kategori ini
            $jawaban = $db->table('jawaban_ujian')
                         ->whereIn('soal_id', $soalIds)
                         ->where('ujian_id', $ujianId)
                         ->where('peserta_id', $pesertaId)
                         ->get()
                         ->getResultArray();

            $benar = 0;
            $salah = 0;
            $tidakDijawab = 0;

            // Create map of jawaban by soal_id
            $jawabanMap = [];
            foreach ($jawaban as $j) {
                $jawabanMap[$j['soal_id']] = $j['jawaban'];
            }

            // Hitung skor per soal
            foreach ($soalList as $soal) {
                $jawabanPeserta = $jawabanMap[$soal['id']] ?? null;

                if (empty($jawabanPeserta)) {
                    $tidakDijawab++;
                } elseif ($jawabanPeserta === $soal['kunci_jawaban']) {
                    $benar++;
                } else {
                    $salah++;
                }
            }

            // Hitung grade
            $grade = $jumlahSoal > 0 ? round(($benar / $jumlahSoal) * 100, 2) : 0;
            $status = $grade >= $passingGrade ? 'LULUS' : 'TIDAK LULUS';

            $hasil[] = [
                'nama_kategori' => $namaKategori,
                'jumlah_soal' => $jumlahSoal,
                'benar' => $benar,
                'salah' => $salah,
                'tidak_dijawab' => $tidakDijawab,
                'grade' => $grade,
                'passing_grade' => $passingGrade,
                'status' => $status
            ];
        }

        return $hasil;
    }

    private function hitungRataRataPencapaian($hasil)
    {
        // Jika tidak ada kategori_data, gunakan nilai ujian
        if (empty($hasil['kategori_data'])) {
            return $hasil['nilai'];
        }

        $kategoriData = json_decode($hasil['kategori_data'], true);
        if (!$kategoriData || !is_array($kategoriData)) {
            return $hasil['nilai'];
        }

        // Hitung hasil per mata pelajaran
        $hasilPerMataPelajaran = $this->hitungHasilPerMataPelajaran(
            $hasil['ujian_id'],
            $hasil['peserta_id'],
            $kategoriData
        );

        if (empty($hasilPerMataPelajaran)) {
            return $hasil['nilai'];
        }

        // PERBAIKAN: Hitung rata-rata grade langsung (tanpa dibagi passing grade)
        $totalGrade = 0;
        foreach ($hasilPerMataPelajaran as $hasilMapel) {
            $totalGrade += $hasilMapel['grade']; // Langsung gunakan grade, jangan dibagi passing grade
        }

        return count($hasilPerMataPelajaran) > 0 ?
            round($totalGrade / count($hasilPerMataPelajaran), 2) : $hasil['nilai'];
    }

    private function hitungStatistikPencapaian($hasil_ujian)
    {
        $total_ujian = count($hasil_ujian);
        $total_lulus = 0;
        $total_pencapaian = 0;
        $pencapaian_tertinggi = 0;

        foreach ($hasil_ujian as $hasil) {
            $pencapaian = $hasil['rata_rata_pencapaian'] ?? 0;
            $total_pencapaian += $pencapaian;

            if ($hasil['is_lulus'] ?? false) {
                $total_lulus++;
            }

            if ($pencapaian > $pencapaian_tertinggi) {
                $pencapaian_tertinggi = $pencapaian;
            }
        }

        $rata_rata_pencapaian = $total_ujian > 0 ? round($total_pencapaian / $total_ujian, 0) : 0;

        return [
            'total_ujian' => $total_ujian,
            'lulus' => $total_lulus,
            'rata_rata' => $rata_rata_pencapaian,
            'tertinggi' => round($pencapaian_tertinggi, 0)
        ];
    }

    private function tentukanKelulusan($hasil)
    {
        $passingGrade = $hasil['passing_grade'] ?? 70;

        // Jika single kategori, gunakan sistem sederhana
        if (empty($hasil['kategori_data'])) {
            return $hasil['rata_rata_pencapaian'] >= $passingGrade;
        }

        // Parse kategori_data untuk multiple kategori
        $kategoriData = json_decode($hasil['kategori_data'], true);
        if (!$kategoriData || !is_array($kategoriData)) {
            return $hasil['rata_rata_pencapaian'] >= $passingGrade;
        }

        // Hitung hasil per mata pelajaran
        $hasilPerMataPelajaran = $this->hitungHasilPerMataPelajaran(
            $hasil['ujian_id'],
            $hasil['peserta_id'],
            $kategoriData
        );

        if (empty($hasilPerMataPelajaran)) {
            return $hasil['rata_rata_pencapaian'] >= $passingGrade;
        }

        // OPSI PENILAIAN - Anda bisa pilih salah satu:

        // OPSI 1: Sistem Sederhana (Rekomendasi)
        // Rata-rata semua mapel harus >= passing grade total
        return $hasil['rata_rata_pencapaian'] >= $passingGrade;

        // OPSI 2: Sistem Ketat (Uncomment jika ingin digunakan)
        // Semua mapel harus lulus individual DAN rata-rata >= passing grade total
        /*
        foreach ($hasilPerMataPelajaran as $hasilMapel) {
            if ($hasilMapel['grade'] < $hasilMapel['passing_grade']) {
                return false; // Jika ada mapel yang tidak lulus, langsung tidak lulus
            }
        }
        return $hasil['rata_rata_pencapaian'] >= $passingGrade;
        */

        // OPSI 3: Sistem Fleksibel (Uncomment jika ingin digunakan)
        // Minimal 70% mapel harus lulus DAN rata-rata >= passing grade total
        /*
        $jumlahMapel = count($hasilPerMataPelajaran);
        $jumlahLulus = 0;

        foreach ($hasilPerMataPelajaran as $hasilMapel) {
            if ($hasilMapel['grade'] >= $hasilMapel['passing_grade']) {
                $jumlahLulus++;
            }
        }

        $persentaseLulus = $jumlahMapel > 0 ? ($jumlahLulus / $jumlahMapel) * 100 : 0;
        return $persentaseLulus >= 70 && $hasil['rata_rata_pencapaian'] >= $passingGrade;
        */
    }
}

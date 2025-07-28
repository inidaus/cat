<?php

namespace App\Controllers\Pembimbing;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\UjianPesertaModel;
use App\Models\UserModel;
use App\Models\JawabanUjianModel;
use App\Models\SoalModel;

class Laporan extends BaseController
{
    protected $ujianModel;
    protected $ujianPesertaModel;
    protected $userModel;
    protected $jawabanModel;
    protected $soalModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->ujianPesertaModel = new UjianPesertaModel();
        $this->userModel = new UserModel();
        $this->jawabanModel = new JawabanUjianModel();
        $this->soalModel = new SoalModel();
    }

    public function index()
    {
        $pembimbingId = session('id');
        
        // Ambil ujian yang dibuat oleh pembimbing ini
        $ujianList = $this->ujianModel
            ->where('pembimbing_id', $pembimbingId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Hitung statistik untuk setiap ujian
        foreach ($ujianList as &$ujian) {
            $totalPeserta = $this->ujianPesertaModel
                ->where('ujian_id', $ujian['id'])
                ->countAllResults();
                
            $pesertaSelesai = $this->ujianPesertaModel
                ->where('ujian_id', $ujian['id'])
                ->whereIn('status', ['selesai', 'timeout'])
                ->countAllResults();
                
            $ujian['total_peserta'] = $totalPeserta;
            $ujian['peserta_selesai'] = $pesertaSelesai;
            
            // Hitung rata-rata nilai
            $hasilUjian = $this->ujianPesertaModel
                ->select('nilai')
                ->where('ujian_id', $ujian['id'])
                ->whereIn('status', ['selesai', 'timeout'])
                ->where('nilai IS NOT NULL')
                ->findAll();
                
            if (!empty($hasilUjian)) {
                $totalNilai = array_sum(array_column($hasilUjian, 'nilai'));
                $ujian['rata_rata'] = round($totalNilai / count($hasilUjian), 1);
            } else {
                $ujian['rata_rata'] = 0;
            }
        }

        $data['ujian_list'] = $ujianList;
        
        return view('pembimbing/laporan/index', $data);
    }

    public function detail($ujianId)
    {
        $pembimbingId = session('id');
        
        // Validasi ujian milik pembimbing ini
        $ujian = $this->ujianModel
            ->where('id', $ujianId)
            ->where('pembimbing_id', $pembimbingId)
            ->first();
            
        if (!$ujian) {
            return redirect()->to('pembimbing/laporan')->with('error', 'Ujian tidak ditemukan');
        }

        // Ambil hasil ujian semua peserta
        $hasilUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->orderBy('ujian_peserta.nilai', 'DESC')
            ->findAll();

        // Hitung hasil detail untuk setiap peserta (seperti di hasil peserta)
        foreach ($hasilUjian as &$hasil) {
            if (in_array($hasil['status'], ['selesai', 'timeout'])) {
                // Ambil semua jawaban peserta
                $jawabanPeserta = $this->jawabanModel
                    ->where('ujian_id', $ujianId)
                    ->where('peserta_id', $hasil['peserta_id'])
                    ->findAll();

                $jawabanBenar = 0;
                $jawabanSalah = 0;
                $tidakDijawab = 0;
                $totalSoal = 0;

                // Hitung berdasarkan kategori data ujian
                if (!empty($ujian['kategori_data'])) {
                    $kategoriData = json_decode($ujian['kategori_data'], true);
                    $hasil['hasil_per_mata_pelajaran'] = [];

                    foreach ($kategoriData as $kd) {
                        // Ambil soal untuk kategori ini
                        $soalKategori = $this->soalModel
                            ->where('kategori_id', $kd['kategori_id'])
                            ->limit((int)$kd['jumlah_soal'])
                            ->findAll();

                        $benarKategori = 0;
                        $salahKategori = 0;
                        $kosongKategori = 0;

                        foreach ($soalKategori as $soal) {
                            $totalSoal++;
                            $jawabanSoal = null;

                            // Cari jawaban untuk soal ini
                            foreach ($jawabanPeserta as $jawaban) {
                                if ($jawaban['soal_id'] == $soal['id']) {
                                    $jawabanSoal = $jawaban;
                                    break;
                                }
                            }

                            if ($jawabanSoal) {
                                // Pastikan field jawaban_benar ada
                                $kunciJawaban = isset($soal['jawaban_benar']) ? $soal['jawaban_benar'] : $soal['kunci_jawaban'];
                                if ($jawabanSoal['jawaban'] === $kunciJawaban) {
                                    $jawabanBenar++;
                                    $benarKategori++;
                                } else {
                                    $jawabanSalah++;
                                    $salahKategori++;
                                }
                            } else {
                                $tidakDijawab++;
                                $kosongKategori++;
                            }
                        }

                        // Hitung grade untuk kategori ini
                        $jumlahSoalKategori = count($soalKategori);
                        $gradeKategori = $jumlahSoalKategori > 0 ? round(($benarKategori / $jumlahSoalKategori) * 100, 1) : 0;

                        // Ambil nama kategori
                        $kategoriModel = new \App\Models\KategoriModel();
                        $kategoriInfo = $kategoriModel->find($kd['kategori_id']);

                        $hasil['hasil_per_mata_pelajaran'][] = [
                            'nama_kategori' => $kategoriInfo ? $kategoriInfo['nama_kategori'] : 'Unknown',
                            'jumlah_soal' => $jumlahSoalKategori,
                            'benar' => $benarKategori,
                            'salah' => $salahKategori,
                            'tidak_dijawab' => $kosongKategori,
                            'grade' => $gradeKategori,
                            'passing_grade' => $kd['passing_grade'],
                            'status' => $gradeKategori >= $kd['passing_grade'] ? 'LULUS' : 'TIDAK LULUS'
                        ];
                    }
                } else {
                    // Single kategori
                    foreach ($jawabanPeserta as $jawaban) {
                        $soal = $this->soalModel->find($jawaban['soal_id']);
                        if ($soal) {
                            $totalSoal++;
                            // Pastikan field jawaban_benar ada
                            $kunciJawaban = isset($soal['jawaban_benar']) ? $soal['jawaban_benar'] : $soal['kunci_jawaban'];
                            if ($jawaban['jawaban'] === $kunciJawaban) {
                                $jawabanBenar++;
                            } else {
                                $jawabanSalah++;
                            }
                        }
                    }
                }

                $hasil['jawaban_benar'] = $jawabanBenar;
                $hasil['jawaban_salah'] = $jawabanSalah;
                $hasil['tidak_dijawab'] = $tidakDijawab;
                $hasil['total_soal'] = $totalSoal;

                // Hitung nilai real
                if ($totalSoal > 0) {
                    $hasil['nilai_real'] = round(($jawabanBenar / $totalSoal) * 100, 1);
                } else {
                    $hasil['nilai_real'] = 0;
                }
            } else {
                $hasil['jawaban_benar'] = 0;
                $hasil['jawaban_salah'] = 0;
                $hasil['tidak_dijawab'] = 0;
                $hasil['total_soal'] = 0;
                $hasil['nilai_real'] = null;
                $hasil['hasil_per_mata_pelajaran'] = [];
            }
        }

        // Hitung statistik berdasarkan nilai_real yang sudah dihitung
        $totalPeserta = count($hasilUjian);
        $pesertaSelesai = 0;
        $pesertaLulus = 0;
        $totalNilai = 0;
        $nilaiTertinggi = 0;
        $nilaiTerendah = 100;

        foreach ($hasilUjian as $hasil) {
            if (in_array($hasil['status'], ['selesai', 'timeout']) && $hasil['nilai_real'] !== null) {
                $pesertaSelesai++;
                $totalNilai += $hasil['nilai_real'];

                if ($hasil['nilai_real'] >= $ujian['passing_grade']) {
                    $pesertaLulus++;
                }

                if ($hasil['nilai_real'] > $nilaiTertinggi) {
                    $nilaiTertinggi = $hasil['nilai_real'];
                }

                if ($hasil['nilai_real'] < $nilaiTerendah) {
                    $nilaiTerendah = $hasil['nilai_real'];
                }
            }
        }

        $rataRata = $pesertaSelesai > 0 ? round($totalNilai / $pesertaSelesai, 1) : 0;
        $persentaseLulus = $pesertaSelesai > 0 ? round(($pesertaLulus / $pesertaSelesai) * 100, 1) : 0;

        $statistik = [
            'total_peserta' => $totalPeserta,
            'peserta_selesai' => $pesertaSelesai,
            'peserta_lulus' => $pesertaLulus,
            'rata_rata' => $rataRata,
            'persentase_lulus' => $persentaseLulus,
            'nilai_tertinggi' => $nilaiTertinggi,
            'nilai_terendah' => $pesertaSelesai > 0 ? $nilaiTerendah : 0
        ];

        // DEBUG: Log statistik untuk debugging
        log_message('debug', 'Statistik Laporan: ' . json_encode($statistik));
        log_message('debug', 'Total Hasil Ujian: ' . count($hasilUjian));

        $data['ujian'] = $ujian;
        $data['hasil_ujian'] = $hasilUjian;
        $data['statistik'] = $statistik;

        return view('pembimbing/laporan/detail', $data);
    }

    public function export($ujianId)
    {
        $pembimbingId = session('id');
        
        // Validasi ujian milik pembimbing ini
        $ujian = $this->ujianModel
            ->where('id', $ujianId)
            ->where('pembimbing_id', $pembimbingId)
            ->first();
            
        if (!$ujian) {
            return redirect()->to('pembimbing/laporan')->with('error', 'Ujian tidak ditemukan');
        }

        // Ambil hasil ujian
        $hasilUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();

        // Set header untuk download CSV
        $filename = 'Laporan_' . str_replace(' ', '_', $ujian['judul']) . '_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Header CSV
        fputcsv($output, [
            'No',
            'NIM',
            'Nama Lengkap',
            'Status',
            'Nilai',
            'Waktu Mulai',
            'Waktu Selesai',
            'Durasi (menit)',
            'Keterangan'
        ]);
        
        // Data CSV
        $no = 1;
        foreach ($hasilUjian as $hasil) {
            $durasi = '';
            if ($hasil['mulai'] && $hasil['selesai']) {
                $waktuMulai = strtotime($hasil['mulai']);
                $waktuSelesai = strtotime($hasil['selesai']);
                $durasi = round(($waktuSelesai - $waktuMulai) / 60, 1);
            }
            
            $keterangan = '';
            if (in_array($hasil['status'], ['selesai', 'timeout']) && $hasil['nilai'] !== null) {
                $keterangan = $hasil['nilai'] >= $ujian['passing_grade'] ? 'LULUS' : 'TIDAK LULUS';
            }
            
            fputcsv($output, [
                $no++,
                $hasil['nim'],
                $hasil['nama_lengkap'],
                ucfirst(str_replace('_', ' ', $hasil['status'])),
                $hasil['nilai'] ?? '-',
                $hasil['mulai'] ? date('d/m/Y H:i', strtotime($hasil['mulai'])) : '-',
                $hasil['selesai'] ? date('d/m/Y H:i', strtotime($hasil['selesai'])) : '-',
                $durasi,
                $keterangan
            ]);
        }
        
        fclose($output);
        exit;
    }

    public function debugDetail($ujianId)
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('pembimbing/laporan');
        }

        $pembimbingId = session('id');

        // Validasi ujian milik pembimbing ini
        $ujian = $this->ujianModel
            ->where('id', $ujianId)
            ->where('pembimbing_id', $pembimbingId)
            ->first();

        if (!$ujian) {
            echo "Ujian tidak ditemukan";
            return;
        }

        echo "<h2>Debug Laporan Detail - {$ujian['judul']}</h2>";

        // Ambil hasil ujian
        $hasilUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->findAll();

        echo "<h3>Data Mentah Hasil Ujian:</h3>";
        echo "<pre>" . print_r($hasilUjian, true) . "</pre>";

        echo "<h3>Jawaban Peserta:</h3>";
        foreach ($hasilUjian as $hasil) {
            if (in_array($hasil['status'], ['selesai', 'timeout'])) {
                echo "<h4>{$hasil['nama_lengkap']} (ID: {$hasil['peserta_id']})</h4>";

                $jawabanPeserta = $this->jawabanModel
                    ->where('ujian_id', $ujianId)
                    ->where('peserta_id', $hasil['peserta_id'])
                    ->findAll();

                echo "<p>Total Jawaban: " . count($jawabanPeserta) . "</p>";

                if (!empty($jawabanPeserta)) {
                    echo "<table border='1'>";
                    echo "<tr><th>Soal ID</th><th>Jawaban</th><th>Kunci</th><th>Status</th></tr>";

                    foreach ($jawabanPeserta as $jawaban) {
                        $soal = $this->soalModel->find($jawaban['soal_id']);
                        $kunci = $soal ? (isset($soal['jawaban_benar']) ? $soal['jawaban_benar'] : $soal['kunci_jawaban']) : 'N/A';
                        $status = ($jawaban['jawaban'] === $kunci) ? 'BENAR' : 'SALAH';

                        echo "<tr>";
                        echo "<td>{$jawaban['soal_id']}</td>";
                        echo "<td>{$jawaban['jawaban']}</td>";
                        echo "<td>$kunci</td>";
                        echo "<td>$status</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                echo "<hr>";
            }
        }

        echo "<p><a href='" . base_url('pembimbing/laporan/detail/' . $ujianId) . "'>Kembali ke Laporan</a></p>";
    }
}

<?php

namespace App\Controllers\Admin;

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
        // Admin bisa melihat semua ujian dari semua pembimbing
        $ujianList = $this->ujianModel
            ->select('ujian.*, users.nama_lengkap as nama_pembimbing')
            ->join('users', 'users.id = ujian.pembimbing_id')
            ->orderBy('ujian.created_at', 'DESC')
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
            
            // Hitung rata-rata nilai real
            $hasilUjian = $this->ujianPesertaModel
                ->where('ujian_id', $ujian['id'])
                ->whereIn('status', ['selesai', 'timeout'])
                ->findAll();
                
            $totalNilai = 0;
            $jumlahNilai = 0;
            
            foreach ($hasilUjian as $hasil) {
                if (in_array($hasil['status'], ['selesai', 'timeout'])) {
                    // Hitung nilai real berdasarkan jawaban
                    $jawabanPeserta = $this->jawabanModel
                        ->where('ujian_id', $ujian['id'])
                        ->where('peserta_id', $hasil['peserta_id'])
                        ->findAll();
                    
                    $jawabanBenar = 0;
                    $totalSoal = count($jawabanPeserta);
                    
                    foreach ($jawabanPeserta as $jawaban) {
                        $soal = $this->soalModel->find($jawaban['soal_id']);
                        if ($soal) {
                            $kunciJawaban = isset($soal['jawaban_benar']) ? $soal['jawaban_benar'] : $soal['kunci_jawaban'];
                            if ($jawaban['jawaban'] === $kunciJawaban) {
                                $jawabanBenar++;
                            }
                        }
                    }
                    
                    if ($totalSoal > 0) {
                        $nilaiReal = round(($jawabanBenar / $totalSoal) * 100, 1);
                        $totalNilai += $nilaiReal;
                        $jumlahNilai++;
                    }
                }
            }
            
            $ujian['rata_rata'] = $jumlahNilai > 0 ? round($totalNilai / $jumlahNilai, 1) : 0;
        }

        $data['ujian_list'] = $ujianList;
        
        return view('admin/laporan/index', $data);
    }

    public function detail($ujianId)
    {
        // Validasi ujian ada
        $ujian = $this->ujianModel->find($ujianId);
        if (!$ujian) {
            return redirect()->to('admin/laporan')->with('error', 'Ujian tidak ditemukan');
        }

        // Ambil hasil ujian semua peserta
        $hasilUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->orderBy('ujian_peserta.nilai', 'DESC')
            ->findAll();

        // Hitung hasil detail untuk setiap peserta (sama seperti pembimbing)
        foreach ($hasilUjian as &$hasil) {
            if ($hasil['status'] === 'selesai') {
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
            if ($hasil['status'] === 'selesai' && $hasil['nilai_real'] !== null) {
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

        $data['ujian'] = $ujian;
        $data['hasil_ujian'] = $hasilUjian;
        $data['statistik'] = $statistik;
        
        return view('admin/laporan/detail', $data);
    }

    public function export($ujianId)
    {
        // Validasi ujian ada
        $ujian = $this->ujianModel->find($ujianId);
        if (!$ujian) {
            return redirect()->to('admin/laporan')->with('error', 'Ujian tidak ditemukan');
        }

        // Ambil hasil ujian
        $hasilUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();

        // Set header untuk download CSV
        $filename = 'Laporan_Admin_' . str_replace(' ', '_', $ujian['judul']) . '_' . date('Y-m-d') . '.csv';
        
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
            if ($hasil['status'] === 'selesai' && $hasil['nilai'] !== null) {
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
}

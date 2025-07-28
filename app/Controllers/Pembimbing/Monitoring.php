<?php

namespace App\Controllers\Pembimbing;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\UjianPesertaModel;
use App\Models\UserModel;
use App\Models\JawabanUjianModel;

class Monitoring extends BaseController
{
    protected $ujianModel;
    protected $ujianPesertaModel;
    protected $userModel;
    protected $jawabanModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->ujianPesertaModel = new UjianPesertaModel();
        $this->userModel = new UserModel();
        $this->jawabanModel = new JawabanUjianModel();
    }

    public function index()
    {
        $pembimbingId = session('id');
        
        // Ambil ujian yang dibuat oleh pembimbing ini
        $ujianList = $this->ujianModel
            ->where('pembimbing_id', $pembimbingId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $data['ujian_list'] = $ujianList;
        
        return view('pembimbing/monitoring/index', $data);
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
            return redirect()->to('pembimbing/monitoring')->with('error', 'Ujian tidak ditemukan');
        }

        // Ambil data peserta yang terdaftar di ujian ini
        $pesertaUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();

        // Hitung progress untuk setiap peserta
        foreach ($pesertaUjian as &$peserta) {
            // Hitung total soal yang sudah dijawab
            $totalJawaban = $this->jawabanModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $peserta['peserta_id'])
                ->countAllResults();

            $peserta['total_jawaban'] = $totalJawaban;

            // Jika status selesai, hitung jawaban benar/salah dan nilai real
            if ($peserta['status'] === 'selesai') {
                $soalModel = new \App\Models\SoalModel();

                // Ambil semua jawaban peserta
                $jawabanPeserta = $this->jawabanModel
                    ->where('ujian_id', $ujianId)
                    ->where('peserta_id', $peserta['peserta_id'])
                    ->findAll();

                $jawabanBenar = 0;
                $jawabanSalah = 0;
                $totalSoal = 0;

                foreach ($jawabanPeserta as $jawaban) {
                    $soal = $soalModel->find($jawaban['soal_id']);
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

                $peserta['jawaban_benar'] = $jawabanBenar;
                $peserta['jawaban_salah'] = $jawabanSalah;
                $peserta['total_soal'] = $totalSoal;

                // Hitung nilai real
                if ($totalSoal > 0) {
                    $peserta['nilai_real'] = round(($jawabanBenar / $totalSoal) * 100, 1);
                } else {
                    $peserta['nilai_real'] = 0;
                }
            } else {
                $peserta['jawaban_benar'] = 0;
                $peserta['jawaban_salah'] = 0;
                $peserta['total_soal'] = 0;
                $peserta['nilai_real'] = null;
            }

            // Hitung waktu pengerjaan jika sudah mulai
            if ($peserta['mulai']) {
                $waktuMulai = strtotime($peserta['mulai']);
                $waktuSekarang = time();
                $waktuSelesai = $peserta['selesai'] ? strtotime($peserta['selesai']) : $waktuSekarang;

                $durasiDetik = $waktuSelesai - $waktuMulai;
                $peserta['durasi_menit'] = round($durasiDetik / 60, 1);
            } else {
                $peserta['durasi_menit'] = 0;
            }
        }

        $data['ujian'] = $ujian;
        $data['peserta_ujian'] = $pesertaUjian;
        
        return view('pembimbing/monitoring/detail', $data);
    }

    public function realtime($ujianId)
    {
        $pembimbingId = session('id');
        
        // Validasi ujian milik pembimbing ini
        $ujian = $this->ujianModel
            ->where('id', $ujianId)
            ->where('pembimbing_id', $pembimbingId)
            ->first();
            
        if (!$ujian) {
            return $this->response->setJSON(['error' => 'Ujian tidak ditemukan']);
        }

        // Ambil data real-time peserta
        $pesertaUjian = $this->ujianPesertaModel
            ->select('ujian_peserta.*, users.nama_lengkap, users.username as nim')
            ->join('users', 'users.id = ujian_peserta.peserta_id')
            ->where('ujian_peserta.ujian_id', $ujianId)
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();

        // Hitung progress real-time
        foreach ($pesertaUjian as &$peserta) {
            $totalJawaban = $this->jawabanModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $peserta['peserta_id'])
                ->countAllResults();

            $peserta['total_jawaban'] = $totalJawaban;

            // Jika selesai, hitung jawaban benar/salah
            if ($peserta['status'] === 'selesai') {
                $soalModel = new \App\Models\SoalModel();
                $jawabanPeserta = $this->jawabanModel
                    ->where('ujian_id', $ujianId)
                    ->where('peserta_id', $peserta['peserta_id'])
                    ->findAll();

                $jawabanBenar = 0;
                $jawabanSalah = 0;

                foreach ($jawabanPeserta as $jawaban) {
                    $soal = $soalModel->find($jawaban['soal_id']);
                    if ($soal) {
                        $kunciJawaban = isset($soal['jawaban_benar']) ? $soal['jawaban_benar'] : $soal['kunci_jawaban'];
                        if ($jawaban['jawaban'] === $kunciJawaban) {
                            $jawabanBenar++;
                        } else {
                            $jawabanSalah++;
                        }
                    }
                }

                $peserta['jawaban_benar'] = $jawabanBenar;
                $peserta['jawaban_salah'] = $jawabanSalah;

                // Hitung nilai real
                $totalSoal = count($jawabanPeserta);
                if ($totalSoal > 0) {
                    $peserta['nilai_real'] = round(($jawabanBenar / $totalSoal) * 100, 1);
                } else {
                    $peserta['nilai_real'] = 0;
                }
            }
            
            // Status real-time
            if ($peserta['status'] === 'sedang_ujian') {
                $waktuMulai = strtotime($peserta['mulai']);
                $waktuSekarang = time();
                $durasiDetik = $waktuSekarang - $waktuMulai;
                $peserta['durasi_menit'] = round($durasiDetik / 60, 1);
                $peserta['status_display'] = 'Sedang Ujian';
                $peserta['status_class'] = 'success';
            } elseif ($peserta['status'] === 'selesai') {
                $peserta['status_display'] = 'Selesai';
                $peserta['status_class'] = 'secondary';
            } else {
                $peserta['status_display'] = 'Belum Mulai';
                $peserta['status_class'] = 'warning';
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $pesertaUjian,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}

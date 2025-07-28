<?php
namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\SoalModel;
use App\Models\JawabanUjianModel;
use App\Models\UjianPesertaModel;
use App\Models\KategoriModel;

class Ujian extends BaseController
{
    protected $ujianModel;
    protected $soalModel;
    protected $jawabanModel;
    protected $ujianPesertaModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->ujianModel        = new UjianModel();
        $this->soalModel         = new SoalModel();
        $this->jawabanModel      = new JawabanUjianModel();
        $this->ujianPesertaModel = new UjianPesertaModel();
        $this->kategoriModel     = new KategoriModel();
    }

    public function index($ujianId)
    {
        $pesertaId = session('id');
        if (!$pesertaId) {
            return redirect()->to('peserta/dashboard');
        }

        $ujian = $this->ujianModel
            ->select('ujian.*, kategori_soal.nama_kategori')
            ->join('kategori_soal', 'kategori_soal.id = ujian.kategori_id')
            ->where('ujian.id', $ujianId)
            ->first();

        if (!$ujian) {
            return redirect()->to('peserta/dashboard')->with('error', 'Ujian tidak ditemukan.');
        }

        $terdaftar = $this->ujianPesertaModel
            ->where('ujian_id', $ujianId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$terdaftar) {
            return redirect()->to('peserta/dashboard')->with('error', 'Anda tidak terdaftar dalam ujian ini.');
        }

        // Cek apakah ujian sudah selesai
        if ($terdaftar['status'] === 'selesai') {
            return redirect()->to('peserta/dashboard')->with('info', 'Ujian ini sudah diselesaikan.');
        }

        // Cek apakah token sudah diverifikasi
        $tokenVerified = session('token_verified_' . $ujianId);
        if (!$tokenVerified) {
            // Siapkan nama kategori untuk token page
            $namaKategori = $ujian['nama_kategori'];
            if (!empty($ujian['kategori_data'])) {
                $kategoriData = json_decode($ujian['kategori_data'], true);
                if ($kategoriData && is_array($kategoriData)) {
                    $kategoriModel = new \App\Models\KategoriModel();
                    $namaKategoriList = [];
                    foreach ($kategoriData as $kd) {
                        $kategori = $kategoriModel->find($kd['kategori_id']);
                        if ($kategori) {
                            $namaKategoriList[] = $kategori['nama_kategori'];
                        }
                    }
                    $namaKategori = implode(', ', $namaKategoriList);
                }
            }

            // Tampilkan halaman token
            $data = [
                'ujian' => $ujian,
                'kategori' => [
                    'nama_kategori' => $namaKategori
                ]
            ];
            return view('peserta/ujian/token', $data);
        }

        // Ambil soal berdasarkan kategori_data dengan pemisahan per mata pelajaran
        $soalListByKategori = [];
        $kategoriInfo = [];

        if (!empty($ujian['kategori_data'])) {
            $kategoriData = json_decode($ujian['kategori_data'], true);
            if ($kategoriData && is_array($kategoriData)) {
                $kategoriModel = new \App\Models\KategoriModel();

                foreach ($kategoriData as $kd) {
                    $soalKategori = $this->soalModel
                        ->where('kategori_id', $kd['kategori_id'])
                        ->findAll($kd['jumlah_soal']);

                    if (count($soalKategori) < $kd['jumlah_soal']) {
                        return redirect()->to('peserta/dashboard')->with('error', 'Soal tidak mencukupi untuk mata pelajaran ini.');
                    }

                    // Acak soal per kategori jika diperlukan
                    if ($ujian['acak_soal']) {
                        shuffle($soalKategori);
                    }

                    $kategori = $kategoriModel->find($kd['kategori_id']);
                    $soalListByKategori[] = [
                        'kategori_id' => $kd['kategori_id'],
                        'nama_kategori' => $kategori['nama_kategori'],
                        'jumlah_soal' => $kd['jumlah_soal'],
                        'passing_grade' => $kd['passing_grade'],
                        'waktu_menit' => $kd['waktu_menit'],
                        'soal' => $soalKategori
                    ];

                    $kategoriInfo[] = [
                        'nama' => $kategori['nama_kategori'],
                        'jumlah_soal' => $kd['jumlah_soal'],
                        'passing_grade' => $kd['passing_grade'],
                        'waktu_menit' => $kd['waktu_menit']
                    ];
                }
            }
        } else {
            // Fallback untuk ujian lama
            $soalList = $this->soalModel
                ->where('kategori_id', $ujian['kategori_id'])
                ->findAll($ujian['jumlah_soal']);

            if (count($soalList) < $ujian['jumlah_soal']) {
                return redirect()->to('peserta/dashboard')->with('error', 'Soal tidak mencukupi untuk ujian ini.');
            }

            if ($ujian['acak_soal']) {
                shuffle($soalList);
            }

            $kategori = $this->kategoriModel->find($ujian['kategori_id']);
            $soalListByKategori[] = [
                'kategori_id' => $ujian['kategori_id'],
                'nama_kategori' => $kategori['nama_kategori'],
                'jumlah_soal' => $ujian['jumlah_soal'],
                'passing_grade' => $ujian['passing_grade'],
                'waktu_menit' => $ujian['waktu_menit'],
                'soal' => $soalList
            ];

            $kategoriInfo[] = [
                'nama' => $kategori['nama_kategori'],
                'jumlah_soal' => $ujian['jumlah_soal'],
                'passing_grade' => $ujian['passing_grade'],
                'waktu_menit' => $ujian['waktu_menit']
            ];
        }

        $jawaban = [];
        foreach ($this->jawabanModel->where([
            'ujian_id' => $ujianId,
            'peserta_id' => $pesertaId
        ])->findAll() as $j) {
            $jawaban[$j['soal_id']] = $j['jawaban'];
        }

        // Waktu mulai ujian peserta
        // Jika belum ada waktu mulai, gunakan waktu sekarang
        $ujianMulai = $terdaftar['mulai'];
        if (!$ujianMulai || $ujianMulai === '0000-00-00 00:00:00') {
            $ujianMulai = date('Y-m-d H:i:s');
            // Update waktu mulai di database
            $this->ujianPesertaModel->update($terdaftar['id'], [
                'mulai' => $ujianMulai,
                'status' => 'sedang_ujian'
            ]);
        }

        // Get kategori info - siapkan nama kategori untuk header
        $namaKategori = $ujian['nama_kategori'];
        if (!empty($ujian['kategori_data'])) {
            $kategoriData = json_decode($ujian['kategori_data'], true);
            if ($kategoriData && is_array($kategoriData)) {
                $kategoriModel = new \App\Models\KategoriModel();
                $namaKategoriList = [];
                foreach ($kategoriData as $kd) {
                    $kategori = $kategoriModel->find($kd['kategori_id']);
                    if ($kategori) {
                        $namaKategoriList[] = $kategori['nama_kategori'];
                    }
                }
                $namaKategori = implode(', ', $namaKategoriList);
            }
        }

        $kategori = ['nama_kategori' => $namaKategori];

        return view('peserta/ujian/index', [
            'ujian'              => $ujian,
            'soalListByKategori' => $soalListByKategori,
            'kategoriInfo'       => $kategoriInfo,
            'jawaban'            => $jawaban,
            'pesertaId'          => $pesertaId,
            'ujianMulai'         => $ujianMulai,
            'kategori'           => $kategori,
        ]);
    }

    public function simpanJawaban()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $data      = $this->request->getPost();
        $ujianId   = $data['ujian_id'];
        $soalId    = $data['soal_id'];
        $jawaban   = $data['jawaban'];
        $pesertaId = session('id');

        if (!$pesertaId) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'unauthorized']);
        }

        // Validasi apakah ujian masih aktif
        $ujianPeserta = $this->ujianPesertaModel
            ->where('ujian_id', $ujianId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$ujianPeserta || $ujianPeserta['status'] === 'selesai') {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error', 
                'message' => 'Ujian sudah diselesaikan'
            ]);
        }

        $existing = $this->jawabanModel
            ->where([
                'ujian_id' => $ujianId,
                'soal_id' => $soalId,
                'peserta_id' => $pesertaId
            ])->first();

        try {
            if ($existing) {
                $this->jawabanModel->update($existing['id'], [
                    'jawaban' => $jawaban,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } else {
                $this->jawabanModel->insert([
                    'ujian_id' => $ujianId,
                    'soal_id' => $soalId,
                    'peserta_id' => $pesertaId,
                    'jawaban' => $jawaban,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            log_message('error', 'Error saving answer: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan jawaban'
            ]);
        }
    }

public function selesai()
    {
        $ujianId = $this->request->getPost('ujian_id');
        $pesertaId = session('id');

        if (!$pesertaId || !$ujianId) {
            return redirect()->to('peserta/dashboard')->with('error', 'Data tidak valid.');
        }

        // Cek apakah peserta terdaftar dalam ujian
        $ujianPeserta = $this->ujianPesertaModel
            ->where('ujian_id', $ujianId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$ujianPeserta) {
            return redirect()->to('peserta/dashboard')->with('error', 'Anda tidak terdaftar dalam ujian ini.');
        }

        if ($ujianPeserta['status'] === 'selesai') {
            return redirect()->to('peserta/dashboard')->with('info', 'Ujian ini sudah diselesaikan sebelumnya.');
        }

        try {
            // Update status ujian peserta
            $updateResult = $this->ujianPesertaModel->update($ujianPeserta['id'], [
                'selesai' => date('Y-m-d H:i:s'), 
                'status' => 'selesai'
            ]);

            if ($updateResult) {
                log_message('info', "Ujian {$ujianId} berhasil diselesaikan oleh peserta {$pesertaId}");
                
                // Jika request AJAX, return JSON
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Ujian berhasil diselesaikan'
                    ]);
                }
                
                return redirect()->to('peserta/dashboard')->with('success', 'Ujian telah diselesaikan dengan sukses.');
            } else {
                throw new \Exception('Gagal mengupdate status ujian');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error finishing exam: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menyelesaikan ujian'
                ]);
            }
            
            return redirect()->to('peserta/dashboard')->with('error', 'Terjadi kesalahan saat menyelesaikan ujian.');
        }
    }

// PERBAIKAN: Method sinkronisasiJawaban yang benar
    public function sinkronisasiJawaban($ujianId = null, $pesertaId = null)
    {
        // Jika dipanggil via AJAX
        if ($this->request->isAJAX()) {
            // PERBAIKAN: Disable error display untuk AJAX request
            ini_set('display_errors', 0);
            error_reporting(0);

            // Clear all output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Start clean output buffer
            ob_start();

            // Set JSON header
            header('Content-Type: application/json', true);

            $ujianId = $this->request->getPost('ujian_id');
            $pesertaId = session('id');
            $jawabanDataJson = $this->request->getPost('jawaban_data');

            // Validasi data
            if (!$pesertaId || !$ujianId || !$jawabanDataJson) {
                return $this->cleanJsonResponse([
                    'status' => 'error',
                    'message' => 'Data tidak lengkap'
                ], 400);
            }

            // Decode JSON jawaban
            $jawabanData = json_decode($jawabanDataJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return $this->cleanJsonResponse([
                    'status' => 'error',
                    'message' => 'Format data jawaban tidak valid'
                ], 400);
            }

            // Validasi ujian peserta
            $ujianPeserta = $this->ujianPesertaModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $pesertaId)
                ->first();

            if (!$ujianPeserta || $ujianPeserta['status'] === 'selesai') {
                return $this->cleanJsonResponse([
                    'status' => 'error',
                    'message' => 'Ujian sudah diselesaikan atau tidak valid'
                ], 400);
            }

            try {
                $berhasilDisimpan = 0;
                foreach ($jawabanData as $soalId => $jawaban) {
                    if (!empty($jawaban) && !empty($soalId)) {
                        // Cek apakah jawaban sudah ada
                        $existing = $this->jawabanModel
                            ->where([
                                'ujian_id' => $ujianId,
                                'soal_id' => $soalId,
                                'peserta_id' => $pesertaId
                            ])->first();

                        if ($existing) {
                            // Update jawaban yang sudah ada
                            $result = $this->jawabanModel->update($existing['id'], [
                                'jawaban' => $jawaban,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        } else {
                            // Insert jawaban baru
                            $result = $this->jawabanModel->insert([
                                'ujian_id' => $ujianId,
                                'soal_id' => $soalId,
                                'peserta_id' => $pesertaId,
                                'jawaban' => $jawaban,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                        if ($result) {
                            $berhasilDisimpan++;
                        }
                    }
                }

                log_message('info', "Sinkronisasi jawaban: {$berhasilDisimpan} jawaban berhasil disimpan untuk peserta {$pesertaId} ujian {$ujianId}");

                return $this->cleanJsonResponse([
                    'status' => 'success',
                    'message' => "{$berhasilDisimpan} jawaban berhasil disinkronisasi"
                ]);

            } catch (\Exception $e) {
                log_message('error', 'Error syncing answers: ' . $e->getMessage());
                return $this->cleanJsonResponse([
                    'status' => 'error',
                    'message' => 'Gagal sinkronisasi jawaban: ' . $e->getMessage()
                ], 500);
            }
        }

        // Jika dipanggil internal (dari method selesai) - untuk backup
        if ($ujianId && $pesertaId) {
            log_message('info', "Internal sync called for ujian {$ujianId} peserta {$pesertaId}");
            return true;
        }

        return false;
    }

    // Fungsi untuk menangani timeout ujian (dipanggil via AJAX)
    public function timeout()
    {
        // PERBAIKAN: Disable error display untuk AJAX request
        ini_set('display_errors', 0);
        error_reporting(0);

        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Start clean output buffer
        ob_start();

        // Set JSON header
        header('Content-Type: application/json', true);

        if (!$this->request->isAJAX()) {
            return $this->cleanJsonResponse([
                'status' => 'error',
                'message' => 'Request harus AJAX'
            ], 400);
        }

        $ujianId = $this->request->getPost('ujian_id');
        $pesertaId = session('id');

        if (!$pesertaId || !$ujianId) {
            return $this->cleanJsonResponse([
                'status' => 'error',
                'message' => 'Data tidak valid'
            ], 400);
        }

        try {
            // PERBAIKAN: Sinkronisasi jawaban sebelum timeout
            $this->sinkronisasiJawaban($ujianId, $pesertaId);

            // Auto-finish ujian karena timeout
            $ujianPeserta = $this->ujianPesertaModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $pesertaId)
                ->first();

            if ($ujianPeserta) {
                $updateResult = $this->ujianPesertaModel->update($ujianPeserta['id'], [
                    'selesai' => date('Y-m-d H:i:s'),
                    'status' => 'selesai' // Ubah dari 'timeout' ke 'selesai' agar muncul di laporan
                ]);

                log_message('info', "Timeout - Update ujian_peserta ID {$ujianPeserta['id']}: " . ($updateResult ? 'SUCCESS' : 'FAILED'));

                // PERBAIKAN: Pastikan data benar-benar tersimpan dengan force reload
                $updatedData = $this->ujianPesertaModel->find($ujianPeserta['id']);
                log_message('info', "Timeout - Verified status: " . json_encode($updatedData));
            }

            // PERBAIKAN: Return JSON yang bersih
            return $this->cleanJsonResponse([
                'status' => 'success',
                'message' => 'Ujian diselesaikan karena timeout'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error timeout exam: ' . $e->getMessage());
            return $this->cleanJsonResponse([
                'status' => 'error',
                'message' => 'Gagal menyelesaikan ujian'
            ], 500);
        }
    }

    // PERBAIKAN: Method untuk return JSON yang benar-benar bersih
    private function cleanJsonResponse($data, $statusCode = 200)
    {
        // Disable error display
        ini_set('display_errors', 0);
        error_reporting(0);

        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Set HTTP status code
        http_response_code($statusCode);

        // Set JSON header
        header('Content-Type: application/json', true);

        // Output JSON and exit immediately
        echo json_encode($data);
        die();
    }

    public function verifyToken()
    {
        $ujianId = $this->request->getPost('ujian_id');
        $token = $this->request->getPost('token');
        $pesertaId = session('id');

        if (!$ujianId || !$token || !$pesertaId) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        // Get ujian data
        $ujian = $this->ujianModel->find($ujianId);
        if (!$ujian) {
            return redirect()->to('peserta/dashboard')->with('error', 'Ujian tidak ditemukan.');
        }

        // Cek apakah peserta terdaftar
        $terdaftar = $this->ujianPesertaModel
            ->where('ujian_id', $ujianId)
            ->where('peserta_id', $pesertaId)
            ->first();

        if (!$terdaftar) {
            return redirect()->to('peserta/dashboard')->with('error', 'Anda tidak terdaftar dalam ujian ini.');
        }

        // Generate token yang benar (bisa dari database atau algoritma)
        $correctToken = $this->generateUjianToken($ujianId);

        if (strtoupper($token) !== strtoupper($correctToken)) {
            return redirect()->back()->with('error', 'Token tidak valid. Silakan periksa kembali token yang diberikan pembimbing.');
        }

        // Token benar, simpan ke session
        session()->set('token_verified_' . $ujianId, true);

        // Update status ujian peserta menjadi sedang_ujian
        $this->ujianPesertaModel->update($terdaftar['id'], [
            'status' => 'sedang_ujian',
            'mulai' => date('Y-m-d H:i:s')
        ]);

        // Redirect ke halaman ujian
        return redirect()->to('peserta/ujian/' . $ujianId);
    }

    private function generateUjianToken($ujianId)
    {
        // Load token helper
        helper('token');

        // Get ujian data untuk timestamp
        $ujian = $this->ujianModel->find($ujianId);
        if (!$ujian) {
            return false;
        }

        // Generate token menggunakan helper function
        return generateUjianToken($ujianId, $ujian['updated_at'], $ujian['created_at']);
    }
}
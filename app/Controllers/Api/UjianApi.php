<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\UjianPesertaModel;
use App\Models\JawabanUjianModel;

class UjianApi extends BaseController
{
    protected $ujianModel;
    protected $ujianPesertaModel;
    protected $jawabanModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->ujianPesertaModel = new UjianPesertaModel();
        $this->jawabanModel = new JawabanUjianModel();
    }

    // Method untuk sinkronisasi jawaban yang benar-benar bersih
    public function sinkronisasiJawaban()
    {
        // Disable semua error display
        ini_set('display_errors', 0);
        error_reporting(0);
        
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set JSON header
        header('Content-Type: application/json', true);
        
        try {
            // Validasi AJAX request
            if (!$this->request->isAJAX()) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Request harus AJAX'], 400);
            }

            $ujianId = $this->request->getPost('ujian_id');
            $pesertaId = session('id');
            $jawabanDataJson = $this->request->getPost('jawaban_data');

            // Validasi data
            if (!$pesertaId || !$ujianId || !$jawabanDataJson) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Data tidak lengkap'], 400);
            }

            // Decode JSON jawaban
            $jawabanData = json_decode($jawabanDataJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Format data jawaban tidak valid'], 400);
            }

            // Validasi ujian peserta
            $ujianPeserta = $this->ujianPesertaModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $pesertaId)
                ->first();

            if (!$ujianPeserta || $ujianPeserta['status'] === 'selesai') {
                $this->jsonResponse(['status' => 'error', 'message' => 'Ujian sudah diselesaikan atau tidak valid'], 400);
            }

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

            $this->jsonResponse([
                'status' => 'success',
                'message' => "{$berhasilDisimpan} jawaban berhasil disinkronisasi"
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error syncing answers: ' . $e->getMessage());
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Gagal sinkronisasi jawaban'
            ], 500);
        }
    }

    // Method untuk timeout ujian yang benar-benar bersih
    public function timeout()
    {
        // Disable semua error display
        ini_set('display_errors', 0);
        error_reporting(0);
        
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set JSON header
        header('Content-Type: application/json', true);
        
        try {
            // Validasi AJAX request
            if (!$this->request->isAJAX()) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Request harus AJAX'], 400);
            }

            $ujianId = $this->request->getPost('ujian_id');
            $pesertaId = session('id');

            if (!$pesertaId || !$ujianId) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Data tidak valid'], 400);
            }

            // Auto-finish ujian karena timeout
            $ujianPeserta = $this->ujianPesertaModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $pesertaId)
                ->first();

            if ($ujianPeserta) {
                $updateResult = $this->ujianPesertaModel->update($ujianPeserta['id'], [
                    'selesai' => date('Y-m-d H:i:s'),
                    'status' => 'selesai'
                ]);
                
                log_message('info', "Timeout - Update ujian_peserta ID {$ujianPeserta['id']}: " . ($updateResult ? 'SUCCESS' : 'FAILED'));
            }

            $this->jsonResponse([
                'status' => 'success',
                'message' => 'Ujian diselesaikan karena timeout'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error timeout exam: ' . $e->getMessage());
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Gagal menyelesaikan ujian'
            ], 500);
        }
    }

    // Method test untuk memastikan API bekerja
    public function test()
    {
        // Disable semua error display
        ini_set('display_errors', 0);
        error_reporting(0);

        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Set JSON header
        header('Content-Type: application/json', true);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'API UjianApi bekerja dengan baik',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }

    // Method untuk return JSON yang benar-benar bersih
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode($data);
        die();
    }
}

<?php
namespace App\Controllers\Peserta;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\UjianPesertaModel;
use App\Models\KategoriModel;
use App\Models\HasilUjianModel;
use App\Models\SoalModel;
use App\Models\JawabanUjianModel;

class Dashboard extends BaseController
{
    protected $ujianModel;
    protected $ujianPesertaModel;
    protected $kategoriModel;
    protected $hasilUjianModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->ujianPesertaModel = new UjianPesertaModel();
        $this->kategoriModel = new KategoriModel();
        $this->hasilUjianModel = new HasilUjianModel();
    }

    public function index()
    {
        // Set timezone sederhana untuk menghindari masalah waktu
        date_default_timezone_set('Asia/Jakarta');

        $userId = session('id');

        // Ambil data peserta lengkap untuk menampilkan NIM
        $userModel = new \App\Models\UserModel();
        $dataPeserta = $userModel->find($userId);
        $now = date('Y-m-d H:i:s');

        // Get ujian yang diikuti peserta
        $ujianPeserta = $this->ujianPesertaModel
            ->where('peserta_id', $userId)
            ->findAll();

        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        // Create status map untuk cek apakah ujian sudah dikerjakan
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }

        $ujian_aktif = [];
        $ujian_mendatang = [];
        $ujian_selesai = [];

        // Track ujian yang sudah selesai untuk menghindari duplikasi
        $ujianSelesaiIds = [];

        if (!empty($ujianIdArray)) {
            // Ambil data ujian
            $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->orderBy('mulai', 'ASC')->findAll();

            // Process kategori
            $kategoriModel = new \App\Models\KategoriModel();
            foreach ($ujianList as &$ujian) {
                // Handle multiple kategori
                if (!empty($ujian['kategori_data'])) {
                    $kategoriData = json_decode($ujian['kategori_data'], true);
                    if ($kategoriData && is_array($kategoriData)) {
                        $namaKategoriList = [];
                        foreach ($kategoriData as $kd) {
                            $kategori = $kategoriModel->find($kd['kategori_id']);
                            if ($kategori) {
                                $namaKategoriList[] = $kategori['nama_kategori'];
                            }
                        }
                        $ujian['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                        $ujian['nama_kategori'] = $namaKategoriList[0] ?? 'Umum';
                    }
                } else {
                    // Single kategori
                    $kategori = $kategoriModel->find($ujian['kategori_id']);
                    $ujian['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
                }
            }

            // Process status ujian
            foreach ($ujianList as $ujian) {
                $ujianId = $ujian['id'];
                $status = $statusMap[$ujianId] ?? 'belum_mulai';
                $waktuMulai = strtotime($ujian['mulai']);
                $waktuSekarang = strtotime($now);
                $waktuSelesai = $waktuMulai + (($ujian['waktu_menit'] ?? 60) * 60);

                if ($waktuMulai <= $waktuSekarang && $waktuSekarang <= $waktuSelesai) {
                    // Ujian sedang berlangsung
                    if ($status === 'selesai') {
                        $ujian['status_peserta'] = 'sudah_dikerjakan';
                        $ujian_selesai[] = $ujian;
                        $ujianSelesaiIds[] = $ujianId;
                    } else {
                        $ujian['status_peserta'] = 'aktif';
                        $ujian_aktif[] = $ujian;
                    }
                } elseif ($waktuSekarang > $waktuSelesai) {
                    // Ujian sudah berakhir
                    $ujian['status_peserta'] = 'berakhir';
                    if ($status === 'selesai') {
                        $ujian_selesai[] = $ujian;
                        $ujianSelesaiIds[] = $ujianId;
                    } else {
                        $ujian['status_peserta'] = 'terlewat';
                        $ujian_selesai[] = $ujian;
                    }
                }
                // Ujian mendatang akan dihandle oleh method terpisah
            }
        }

        // PERBAIKAN: Ambil statistik hasil ujian seperti di halaman hasil
        $statistik_hasil = $this->getStatistikHasil($userId);

        // Hitung statistik ujian (untuk card statistik)
        $totalUjian = count($ujian_aktif) + count($ujian_mendatang) + count($ujian_selesai);
        $totalSelesai = count($ujian_selesai);

        $statistik = [
            'total_ujian' => $totalUjian,
            'ujian_selesai' => $totalSelesai,
            'rata_rata' => $statistik_hasil['rata_rata'], // Gunakan data dari statistik hasil
            'tingkat_kelulusan' => $totalSelesai > 0 ? round(($totalSelesai / $totalUjian) * 100, 2) : 0
        ];

        // PERBAIKAN: Gunakan logic yang benar untuk semua ujian mendatang
        // 1. Ambil ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        $ujian_mendatang = [];
        if (!empty($ujianIdArray)) {
            // 2. Status map
            $statusMap = [];
            foreach ($ujianPeserta as $up) {
                $statusMap[$up['ujian_id']] = $up['status'];
            }

            // 3. Ambil data ujian
            $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();

            // 4. Process kategori
            $kategoriModel = new \App\Models\KategoriModel();
            foreach ($ujianList as $key => $ujian) {
                if (!empty($ujian['kategori_data'])) {
                    $kategoriData = json_decode($ujian['kategori_data'], true);
                    if ($kategoriData && is_array($kategoriData)) {
                        $namaKategoriList = [];
                        foreach ($kategoriData as $kd) {
                            $kategori = $kategoriModel->find($kd['kategori_id']);
                            if ($kategori) {
                                $namaKategoriList[] = $kategori['nama_kategori'];
                            }
                        }
                        $ujianList[$key]['nama_kategori'] = implode(', ', $namaKategoriList);
                        $ujianList[$key]['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                    } else {
                        $ujianList[$key]['nama_kategori'] = 'Multiple Kategori';
                    }
                } else {
                    // Single kategori
                    $kategori = $kategoriModel->find($ujian['kategori_id']);
                    $ujianList[$key]['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
                }
            }

            // 5. Filter ujian mendatang
            $processedIds = [];

            foreach ($ujianList as $ujian) {
                $ujianId = $ujian['id'];

                // Skip jika sudah diproses
                if (in_array($ujianId, $processedIds)) {
                    continue;
                }
                $processedIds[] = $ujianId;

                $status = $statusMap[$ujianId] ?? 'belum_mulai';
                $waktuMulai = strtotime($ujian['mulai']);
                $waktuSekarang = strtotime($now);

                // Filter condition
                if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                    $ujian['status_peserta'] = 'belum_dimulai';
                    $ujian_mendatang[] = $ujian;
                }
            }
        }
        $ujian_aktif = $this->forceGetUjianAktif($now);

        $data = [
            'ujian_aktif' => $ujian_aktif,
            'ujian_mendatang' => $ujian_mendatang,
            'ujian_selesai' => $ujian_selesai,
            'statistik_hasil' => $statistik_hasil, // Data statistik hasil ujian
            'statistik' => $statistik,
            'data_peserta' => $dataPeserta // Data peserta untuk menampilkan NIM
        ];

        return view('peserta/dashboard', $data);
    }

    private function getUjianMendatangFixed($userId, $now)
    {
        // Ambil ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        if (empty($ujianIdArray)) {
            return [];
        }

        // Status map
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }

        // Ambil data ujian
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();

        // Process kategori
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($ujianList as &$ujian) {
            // Handle multiple kategori
            if (!empty($ujian['kategori_data'])) {
                $kategoriData = json_decode($ujian['kategori_data'], true);
                if ($kategoriData && is_array($kategoriData)) {
                    $namaKategoriList = [];
                    foreach ($kategoriData as $kd) {
                        $kategori = $kategoriModel->find($kd['kategori_id']);
                        if ($kategori) {
                            $namaKategoriList[] = $kategori['nama_kategori'];
                        }
                    }
                    $ujian['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                    $ujian['nama_kategori'] = $namaKategoriList[0] ?? 'Umum';
                } else {
                    $ujian['nama_kategori'] = 'Kategori Error';
                }
            } else {
                // Single kategori
                $kategori = $kategoriModel->find($ujian['kategori_id']);
                $ujian['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
            }
        }

        $ujian_mendatang = [];
        $processedIds = []; // Anti duplikasi

        foreach ($ujianList as $ujian) {
            $ujianId = $ujian['id'];

            // Skip jika sudah diproses (anti duplikasi)
            if (in_array($ujianId, $processedIds)) {
                continue;
            }
            $processedIds[] = $ujianId;

            $status = $statusMap[$ujianId] ?? 'belum_mulai';
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);

            // Hanya tambahkan jika waktu masih mendatang dan belum selesai
            if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                $ujian['status_peserta'] = 'belum_dimulai';
                $ujian_mendatang[] = $ujian;
            }
        }

        return $ujian_mendatang;
    }

    public function setUjianTime()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        date_default_timezone_set('Asia/Jakarta');
        $now = time();

        // Set UJIAN 1 ke 2 menit dari sekarang
        $ujian1 = $this->ujianModel->where('judul', 'UJIAN 1')->first();
        if ($ujian1) {
            $waktuUjian1 = date('Y-m-d H:i:s', $now + (2 * 60)); // +2 menit
            $this->ujianModel->update($ujian1['id'], ['mulai' => $waktuUjian1]);
            echo "<p>UJIAN 1 diset ke: $waktuUjian1</p>";
        }

        // Set UJIAN 2 ke 5 menit dari sekarang
        $ujian2 = $this->ujianModel->where('judul', 'UJIAN 2')->first();
        if ($ujian2) {
            $waktuUjian2 = date('Y-m-d H:i:s', $now + (5 * 60)); // +5 menit
            $this->ujianModel->update($ujian2['id'], ['mulai' => $waktuUjian2]);
            echo "<p>UJIAN 2 diset ke: $waktuUjian2</p>";
        }

        // Set UJIAN 3 ke 8 menit dari sekarang
        $ujian3 = $this->ujianModel->where('judul', 'UJIAN 3')->first();
        if ($ujian3) {
            $waktuUjian3 = date('Y-m-d H:i:s', $now + (8 * 60)); // +8 menit
            $this->ujianModel->update($ujian3['id'], ['mulai' => $waktuUjian3]);
            echo "<p>UJIAN 3 diset ke: $waktuUjian3</p>";
        }

        // Set UJIAN 4 ke 60 menit dari sekarang (lebih jauh lagi)
        $ujian4 = $this->ujianModel->where('judul', 'UJIAN 4')->first();
        if ($ujian4) {
            $waktuUjian4 = date('Y-m-d H:i:s', $now + (60 * 60)); // +60 menit (1 jam)
            $this->ujianModel->update($ujian4['id'], ['mulai' => $waktuUjian4]);
            echo "<p>UJIAN 4 diset ke: $waktuUjian4</p>";
        }

        // TAMBAHAN: Set UJIAN 5 jika ada
        $ujian5 = $this->ujianModel->where('judul', 'UJIAN 5')->first();
        if ($ujian5) {
            $waktuUjian5 = date('Y-m-d H:i:s', $now + (15 * 60)); // +15 menit
            $this->ujianModel->update($ujian5['id'], ['mulai' => $waktuUjian5]);
            echo "<p>UJIAN 5 diset ke: $waktuUjian5</p>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    public function debugUjianMendatang()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        date_default_timezone_set('Asia/Jakarta');
        $userId = session('id');
        $now = date('Y-m-d H:i:s');

        echo "<h2>Debug Ujian Mendatang</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";
        echo "<p><strong>Now:</strong> $now</p>";

        // Test method getUjianMendatangFixed
        $ujian_mendatang = $this->getUjianMendatangFixed($userId, $now);

        echo "<h3>Hasil getUjianMendatangFixed:</h3>";
        echo "<p><strong>Count:</strong> " . count($ujian_mendatang) . "</p>";

        foreach ($ujian_mendatang as $idx => $ujian) {
            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<h4>[$idx] {$ujian['judul']} (ID: {$ujian['id']})</h4>";
            echo "<p><strong>Mulai:</strong> {$ujian['mulai']}</p>";
            echo "<p><strong>Kategori:</strong> " . ($ujian['nama_kategori'] ?? 'NULL') . "</p>";
            echo "<p><strong>Kategori Multiple:</strong> " . ($ujian['nama_kategori_multiple'] ?? 'NULL') . "</p>";
            echo "</div>";
        }

        // Debug step by step
        echo "<h3>Debug Step by Step:</h3>";

        // 1. Registrasi
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');
        echo "<p><strong>1. Ujian ID Array:</strong> [" . implode(', ', $ujianIdArray) . "]</p>";

        // 2. Data ujian
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();
        echo "<p><strong>2. Ujian List Count:</strong> " . count($ujianList) . "</p>";

        foreach ($ujianList as $ujian) {
            echo "<p>- ID: {$ujian['id']}, Judul: {$ujian['judul']}, Mulai: {$ujian['mulai']}</p>";
        }

        // 3. Status map
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }
        echo "<p><strong>3. Status Map:</strong> " . json_encode($statusMap) . "</p>";

        // 4. Filter waktu
        echo "<h4>4. Filter Waktu:</h4>";
        foreach ($ujianList as $ujian) {
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);
            $status = $statusMap[$ujian['id']] ?? 'belum_mulai';

            echo "<div style='border: 1px solid #333; padding: 10px; margin: 5px 0;'>";
            echo "<p><strong>{$ujian['judul']}:</strong></p>";
            echo "<p>Waktu Mulai: {$ujian['mulai']} (timestamp: $waktuMulai)</p>";
            echo "<p>Waktu Sekarang: $now (timestamp: $waktuSekarang)</p>";
            echo "<p>Status: $status</p>";
            echo "<p>Kondisi: waktuMulai > waktuSekarang = " . ($waktuMulai > $waktuSekarang ? 'TRUE' : 'FALSE') . "</p>";
            echo "<p>Status !== 'selesai' = " . ($status !== 'selesai' ? 'TRUE' : 'FALSE') . "</p>";

            if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                echo "<p style='color: green; font-weight: bold;'>✅ AKAN DITAMBAHKAN ke ujian_mendatang</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ TIDAK DITAMBAHKAN</p>";
            }
            echo "</div>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
        echo "<p><a href='" . base_url('peserta/auto-register-all') . "'>Auto Register ke Semua Ujian</a></p>";
        echo "<p><a href='" . base_url('peserta/update-waktu-ujian') . "'>Update Waktu Ujian ke Masa Depan</a></p>";
    }

    public function updateWaktuUjian()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        echo "<h2>Update Waktu Ujian ke Masa Depan</h2>";

        $ujianModel = new \App\Models\UjianModel();
        $now = time();

        // Ambil ujian yang waktu mulainya sudah lewat tapi statusnya belum selesai
        $ujianLewat = $ujianModel
            ->where('mulai <', date('Y-m-d H:i:s', $now))
            ->findAll();

        echo "<p><strong>Ujian yang waktu mulainya sudah lewat:</strong> " . count($ujianLewat) . "</p>";

        foreach ($ujianLewat as $ujian) {
            // Update waktu mulai ke 5 menit dari sekarang
            $waktuBaruTimestamp = $now + (5 * 60); // 5 menit dari sekarang
            $waktuBaru = date('Y-m-d H:i:s', $waktuBaruTimestamp);

            $ujianModel->update($ujian['id'], [
                'mulai' => $waktuBaru
            ]);

            echo "<p style='color: green;'>✅ {$ujian['judul']} (ID: {$ujian['id']}) - Waktu diubah ke: $waktuBaru</p>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    public function autoRegisterAll()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        $userId = session('id');

        echo "<h2>Auto Register ke Semua Ujian</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";

        // Ambil semua ujian
        $allUjian = $this->ujianModel->findAll();
        echo "<p><strong>Total ujian di database:</strong> " . count($allUjian) . "</p>";

        // Cek registrasi yang sudah ada
        $existingRegistrations = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $registeredUjianIds = array_column($existingRegistrations, 'ujian_id');

        echo "<p><strong>Sudah terdaftar di ujian:</strong> [" . implode(', ', $registeredUjianIds) . "]</p>";

        // Register ke ujian yang belum terdaftar
        foreach ($allUjian as $ujian) {
            if (!in_array($ujian['id'], $registeredUjianIds)) {
                $result = $this->ujianPesertaModel->insert([
                    'ujian_id' => $ujian['id'],
                    'peserta_id' => $userId,
                    'status' => 'belum_mulai'
                ]);

                if ($result) {
                    echo "<p style='color: green;'>✅ Berhasil terdaftar ke: {$ujian['judul']}</p>";
                } else {
                    echo "<p style='color: red;'>❌ Gagal terdaftar ke: {$ujian['judul']}</p>";
                }
            } else {
                echo "<p style='color: blue;'>ℹ️ Sudah terdaftar di: {$ujian['judul']}</p>";
            }
        }

        // Hapus duplikasi jika ada
        echo "<h3>Hapus Duplikasi:</h3>";
        $allRegistrations = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $seenUjianIds = [];
        $duplicates = [];

        foreach ($allRegistrations as $reg) {
            if (in_array($reg['ujian_id'], $seenUjianIds)) {
                $duplicates[] = $reg['id'];
            } else {
                $seenUjianIds[] = $reg['ujian_id'];
            }
        }

        if (!empty($duplicates)) {
            foreach ($duplicates as $dupId) {
                $this->ujianPesertaModel->delete($dupId);
                echo "<p style='color: orange;'>🗑️ Hapus duplikasi ID: $dupId</p>";
            }
        } else {
            echo "<p style='color: green;'>✅ Tidak ada duplikasi</p>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    private function forceGetBothUjian($now)
    {
        $userId = session('id');
        $ujian_mendatang = [];

        // PERBAIKAN: Ambil semua ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        if (empty($ujianIdArray)) {
            return [];
        }

        // Status map
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }

        // Ambil data ujian
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();

        // Process kategori
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($ujianList as &$ujian) {
            // Handle multiple kategori
            if (!empty($ujian['kategori_data'])) {
                $kategoriData = json_decode($ujian['kategori_data'], true);
                if ($kategoriData && is_array($kategoriData)) {
                    $namaKategoriList = [];
                    foreach ($kategoriData as $kd) {
                        $kategori = $kategoriModel->find($kd['kategori_id']);
                        if ($kategori) {
                            $namaKategoriList[] = $kategori['nama_kategori'];
                        }
                    }
                    $ujian['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                    $ujian['nama_kategori'] = $namaKategoriList[0] ?? 'Umum';
                } else {
                    $ujian['nama_kategori'] = 'Kategori Error';
                }
            } else {
                // Single kategori
                $kategori = $kategoriModel->find($ujian['kategori_id']);
                $ujian['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
            }
        }

        // Filter ujian mendatang
        $processedIds = [];
        $ujian_mendatang = [];

        foreach ($ujianList as $ujian) {
            $ujianId = $ujian['id'];

            log_message('debug', "Processing Ujian ID: $ujianId, Judul: {$ujian['judul']}");

            // Skip jika sudah diproses (anti duplikasi)
            if (in_array($ujianId, $processedIds)) {
                log_message('debug', "Skip - sudah diproses");
                continue;
            }
            $processedIds[] = $ujianId;

            $status = $statusMap[$ujianId] ?? 'belum_mulai';
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);

            log_message('debug', "Status: $status, Waktu Mulai: {$ujian['mulai']} ($waktuMulai), Waktu Sekarang: $now ($waktuSekarang)");
            log_message('debug', "Kondisi: waktuMulai > waktuSekarang = " . ($waktuMulai > $waktuSekarang ? 'TRUE' : 'FALSE'));
            log_message('debug', "Kondisi: status !== 'selesai' = " . ($status !== 'selesai' ? 'TRUE' : 'FALSE'));

            // Hanya tambahkan jika waktu masih mendatang dan belum selesai
            if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                $ujian['status_peserta'] = 'belum_dimulai';
                $ujian_mendatang[] = $ujian;
                log_message('debug', "✅ DITAMBAHKAN ke ujian_mendatang");
            } else {
                log_message('debug', "❌ TIDAK DITAMBAHKAN");
            }
        }

        log_message('debug', "Final ujian_mendatang count: " . count($ujian_mendatang));
        log_message('debug', "=== getUjianMendatangFixed END ===");

        return $ujian_mendatang;
    }

    private function forceGetUjianAktif($now)
    {
        $userId = session('id');
        $ujian_aktif = [];

        // PERBAIKAN: Ambil semua ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        if (empty($ujianIdArray)) {
            return [];
        }

        // Status map
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }

        // Ambil data ujian
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();

        // Process kategori
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($ujianList as &$ujian) {
            // Handle multiple kategori
            if (!empty($ujian['kategori_data'])) {
                $kategoriData = json_decode($ujian['kategori_data'], true);
                if ($kategoriData && is_array($kategoriData)) {
                    $namaKategoriList = [];
                    foreach ($kategoriData as $kd) {
                        $kategori = $kategoriModel->find($kd['kategori_id']);
                        if ($kategori) {
                            $namaKategoriList[] = $kategori['nama_kategori'];
                        }
                    }
                    $ujian['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                    $ujian['nama_kategori'] = $namaKategoriList[0] ?? 'Umum';
                } else {
                    $ujian['nama_kategori'] = 'Kategori Error';
                }
            } else {
                // Single kategori
                $kategori = $kategoriModel->find($ujian['kategori_id']);
                $ujian['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
            }
        }

        // Filter ujian aktif
        $processedIds = [];
        foreach ($ujianList as $ujian) {
            $ujianId = $ujian['id'];

            // Skip jika sudah diproses (anti duplikasi)
            if (in_array($ujianId, $processedIds)) {
                continue;
            }
            $processedIds[] = $ujianId;

            $status = $statusMap[$ujianId] ?? 'belum_mulai';
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);
            $waktuSelesai = $waktuMulai + (($ujian['waktu_menit'] ?? 60) * 60);

            // Hanya tambahkan jika ujian sedang berlangsung
            if ($waktuMulai <= $waktuSekarang && $waktuSekarang <= $waktuSelesai && $status !== 'selesai') {
                $ujian['status_peserta'] = 'aktif';
                $ujian_aktif[] = $ujian;
            }
        }

        return $ujian_aktif;
    }

    private function hitungRataRataPencapaian($hasil)
    {
        // Jika tidak ada kategori_data, gunakan nilai langsung
        if (empty($hasil['kategori_data'])) {
            return $hasil['nilai'];
        }

        // Parse kategori_data
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

    private function tentukanKelulusan($hasil)
    {
        $passingGrade = $hasil['passing_grade'] ?? 70;

        // Jika single kategori, gunakan sistem sederhana
        if (empty($hasil['kategori_data'])) {
            $rataRata = $this->hitungRataRataPencapaian($hasil);
            return $rataRata >= $passingGrade;
        }

        // Parse kategori_data untuk multiple kategori
        $kategoriData = json_decode($hasil['kategori_data'], true);
        if (!$kategoriData || !is_array($kategoriData)) {
            $rataRata = $this->hitungRataRataPencapaian($hasil);
            return $rataRata >= $passingGrade;
        }

        // Hitung hasil per mata pelajaran
        $hasilPerMataPelajaran = $this->hitungHasilPerMataPelajaran(
            $hasil['ujian_id'],
            $hasil['peserta_id'],
            $kategoriData
        );

        if (empty($hasilPerMataPelajaran)) {
            $rataRata = $this->hitungRataRataPencapaian($hasil);
            return $rataRata >= $passingGrade;
        }

        // OPSI 1: Sistem Sederhana (Rekomendasi)
        // Rata-rata semua mapel harus >= passing grade total
        $rataRata = $this->hitungRataRataPencapaian($hasil);
        return $rataRata >= $passingGrade;
    }

    private function hitungHasilPerMataPelajaran($ujianId, $pesertaId, $kategoriData)
    {
        $hasilPerMataPelajaran = [];

        foreach ($kategoriData as $kategori) {
            $kategoriId = $kategori['kategori_id'];
            $jumlahSoal = $kategori['jumlah_soal'];

            // Ambil nama kategori
            $kategoriModel = new \App\Models\KategoriModel();
            $kategoriInfo = $kategoriModel->find($kategoriId);
            $namaKategori = $kategoriInfo ? $kategoriInfo['nama_kategori'] : 'Kategori ' . $kategoriId;

            // Hitung jawaban benar untuk kategori ini
            $soalModel = new \App\Models\SoalModel();
            $jawabanModel = new \App\Models\JawabanUjianModel();

            // Ambil soal untuk kategori ini
            $soalKategori = $soalModel->where('kategori_id', $kategoriId)->findAll();
            $soalIds = array_column($soalKategori, 'id');

            if (empty($soalIds)) {
                continue;
            }

            // Ambil jawaban peserta untuk soal kategori ini
            $jawabanPeserta = $jawabanModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $pesertaId)
                ->whereIn('soal_id', $soalIds)
                ->findAll();

            // Hitung jawaban benar
            $benar = 0;
            foreach ($jawabanPeserta as $jawaban) {
                // Cari soal yang sesuai
                $soal = null;
                foreach ($soalKategori as $s) {
                    if ($s['id'] == $jawaban['soal_id']) {
                        $soal = $s;
                        break;
                    }
                }

                if ($soal && $jawaban['jawaban'] == $soal['kunci_jawaban']) {
                    $benar++;
                }
            }

            // Hitung grade
            $grade = $jumlahSoal > 0 ? round(($benar / $jumlahSoal) * 100, 2) : 0;

            $hasilPerMataPelajaran[] = [
                'kategori_id' => $kategoriId,
                'nama_kategori' => $namaKategori,
                'jumlah_soal' => $jumlahSoal,
                'benar' => $benar,
                'salah' => $jumlahSoal - $benar,
                'grade' => $grade,
                'passing_grade' => $kategoriInfo['passing_grade'] ?? 70
            ];
        }

        return $hasilPerMataPelajaran;
    }

    public function debugHasilUjian()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        $userId = session('id');

        echo "<h2>Debug Hasil Ujian Dashboard</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";

        // Cek semua data ujian_peserta untuk user ini
        echo "<h3>1. Semua Data ujian_peserta:</h3>";
        $allUjianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();

        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Ujian ID</th><th>Status</th><th>Mulai</th><th>Selesai</th><th>Nilai</th></tr>";
        foreach ($allUjianPeserta as $up) {
            echo "<tr>";
            echo "<td>{$up['id']}</td>";
            echo "<td>{$up['ujian_id']}</td>";
            echo "<td>{$up['status']}</td>";
            echo "<td>" . ($up['mulai'] ?? 'NULL') . "</td>";
            echo "<td>" . ($up['selesai'] ?? 'NULL') . "</td>";
            echo "<td>" . ($up['nilai'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Cek data yang diambil oleh getHasilByPeserta
        echo "<h3>2. Data dari getHasilByPeserta:</h3>";
        $hasilUjian = $this->hasilUjianModel->getHasilByPeserta($userId);

        echo "<p><strong>Count:</strong> " . count($hasilUjian) . "</p>";

        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Ujian ID</th><th>Judul</th><th>Status</th><th>Nilai</th><th>Kategori Data</th></tr>";
        foreach ($hasilUjian as $hasil) {
            echo "<tr>";
            echo "<td>{$hasil['id']}</td>";
            echo "<td>{$hasil['ujian_id']}</td>";
            echo "<td>" . ($hasil['judul'] ?? 'NULL') . "</td>";
            echo "<td>{$hasil['status']}</td>";
            echo "<td>" . ($hasil['nilai'] ?? 'NULL') . "</td>";
            echo "<td>" . (empty($hasil['kategori_data']) ? 'NULL' : 'Ada') . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    public function resetUjian4()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        $userId = session('id');

        echo "<h2>Reset Status UJIAN 4</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";

        // Cek status UJIAN 4 saat ini
        $ujian4 = $this->ujianModel->where('judul', 'UJIAN 4')->first();
        if (!$ujian4) {
            echo "<p style='color: red;'>❌ UJIAN 4 tidak ditemukan!</p>";
            return;
        }

        echo "<p><strong>UJIAN 4 ID:</strong> {$ujian4['id']}</p>";

        // Cek registrasi peserta di UJIAN 4
        $ujianPeserta4 = $this->ujianPesertaModel
            ->where('ujian_id', $ujian4['id'])
            ->where('peserta_id', $userId)
            ->first();

        if (!$ujianPeserta4) {
            echo "<p style='color: red;'>❌ Peserta belum terdaftar di UJIAN 4!</p>";

            // Daftarkan peserta
            $result = $this->ujianPesertaModel->insert([
                'ujian_id' => $ujian4['id'],
                'peserta_id' => $userId,
                'status' => 'belum_mulai'
            ]);

            if ($result) {
                echo "<p style='color: green;'>✅ Berhasil mendaftarkan peserta ke UJIAN 4</p>";
            } else {
                echo "<p style='color: red;'>❌ Gagal mendaftarkan peserta ke UJIAN 4</p>";
            }
        } else {
            echo "<p><strong>Status saat ini:</strong> {$ujianPeserta4['status']}</p>";
            echo "<p><strong>Mulai:</strong> " . ($ujianPeserta4['mulai'] ?? 'NULL') . "</p>";
            echo "<p><strong>Selesai:</strong> " . ($ujianPeserta4['selesai'] ?? 'NULL') . "</p>";

            // Reset status ke belum_mulai
            $updateResult = $this->ujianPesertaModel->update($ujianPeserta4['id'], [
                'status' => 'belum_mulai',
                'mulai' => null,
                'selesai' => null,
                'nilai' => null
            ]);

            if ($updateResult) {
                echo "<p style='color: green;'>✅ Status UJIAN 4 berhasil direset ke 'belum_mulai'</p>";
            } else {
                echo "<p style='color: red;'>❌ Gagal mereset status UJIAN 4</p>";
            }
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    public function cekDatabase()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        $userId = session('id');
        $db = \Config\Database::connect();

        echo "<h2>Cek Database cbtcat</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";
        echo "<p><strong>Session Data:</strong> " . json_encode(session()->get()) . "</p>";

        // Cek struktur tabel users (peserta)
        echo "<h3>Struktur Tabel Users (Peserta):</h3>";
        $pesertaQuery = $db->query("SELECT * FROM users WHERE id = ? AND role = 'peserta'", [$userId]);
        $pesertaData = $pesertaQuery->getRowArray();

        if ($pesertaData) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Field</th><th>Value</th></tr>";
            foreach ($pesertaData as $field => $value) {
                echo "<tr><td>$field</td><td>" . ($value ?? 'NULL') . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>Data peserta tidak ditemukan!</p>";
        }

        // 1. Cek tabel ujian
        echo "<h3>1. Tabel ujian:</h3>";
        $ujianQuery = $db->query("SELECT id, judul, mulai, waktu_menit, passing_grade, status FROM ujian ORDER BY id");
        $ujianData = $ujianQuery->getResultArray();

        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Judul</th><th>Mulai</th><th>Waktu (menit)</th><th>Passing Grade</th><th>Status</th></tr>";
        foreach ($ujianData as $ujian) {
            echo "<tr>";
            echo "<td>{$ujian['id']}</td>";
            echo "<td>{$ujian['judul']}</td>";
            echo "<td>{$ujian['mulai']}</td>";
            echo "<td>{$ujian['waktu_menit']}</td>";
            echo "<td>{$ujian['passing_grade']}</td>";
            echo "<td>{$ujian['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";

        // 2. Cek tabel ujian_peserta untuk user ini
        echo "<h3>2. Tabel ujian_peserta (User ID: $userId):</h3>";
        $ujianPesertaQuery = $db->query("SELECT up.*, u.judul FROM ujian_peserta up LEFT JOIN ujian u ON u.id = up.ujian_id WHERE up.peserta_id = ? ORDER BY up.ujian_id", [$userId]);
        $ujianPesertaData = $ujianPesertaQuery->getResultArray();

        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>Ujian ID</th><th>Judul Ujian</th><th>Status</th><th>Mulai</th><th>Selesai</th><th>Nilai</th></tr>";
        foreach ($ujianPesertaData as $up) {
            echo "<tr>";
            echo "<td>{$up['id']}</td>";
            echo "<td>{$up['ujian_id']}</td>";
            echo "<td>" . ($up['judul'] ?? 'NULL') . "</td>";
            echo "<td>{$up['status']}</td>";
            echo "<td>" . ($up['mulai'] ?? 'NULL') . "</td>";
            echo "<td>" . ($up['selesai'] ?? 'NULL') . "</td>";
            echo "<td>" . ($up['nilai'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        // 3. Cek waktu sekarang
        echo "<h3>3. Waktu Sekarang:</h3>";
        date_default_timezone_set('Asia/Jakarta');
        $now = date('Y-m-d H:i:s');
        echo "<p><strong>Server Time:</strong> $now</p>";

        // 4. Analisis per ujian
        echo "<h3>4. Analisis per Ujian:</h3>";
        foreach ($ujianData as $ujian) {
            $ujianId = $ujian['id'];
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);
            $waktuSelesai = $waktuMulai + (($ujian['waktu_menit'] ?? 60) * 60);

            // Cari status peserta untuk ujian ini
            $statusPeserta = 'tidak_terdaftar';
            foreach ($ujianPesertaData as $up) {
                if ($up['ujian_id'] == $ujianId) {
                    $statusPeserta = $up['status'];
                    break;
                }
            }

            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<h4>{$ujian['judul']} (ID: $ujianId)</h4>";
            echo "<p><strong>Waktu Mulai:</strong> {$ujian['mulai']} (timestamp: $waktuMulai)</p>";
            echo "<p><strong>Waktu Sekarang:</strong> $now (timestamp: $waktuSekarang)</p>";
            echo "<p><strong>Waktu Selesai:</strong> " . date('Y-m-d H:i:s', $waktuSelesai) . " (timestamp: $waktuSelesai)</p>";
            echo "<p><strong>Status Peserta:</strong> $statusPeserta</p>";

            // Tentukan kategori
            if ($statusPeserta === 'tidak_terdaftar') {
                echo "<p style='color: gray;'>❓ TIDAK TERDAFTAR</p>";
            } elseif ($statusPeserta === 'selesai') {
                echo "<p style='color: blue;'>✅ SUDAH SELESAI</p>";
            } elseif ($waktuMulai > $waktuSekarang) {
                echo "<p style='color: green;'>📅 UJIAN MENDATANG</p>";
            } elseif ($waktuMulai <= $waktuSekarang && $waktuSekarang <= $waktuSelesai) {
                echo "<p style='color: orange;'>🔥 UJIAN AKTIF</p>";
            } else {
                echo "<p style='color: red;'>⏰ UJIAN BERAKHIR</p>";
            }
            echo "</div>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    public function debugGetUjianMendatang()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        date_default_timezone_set('Asia/Jakarta');
        $userId = session('id');
        $now = date('Y-m-d H:i:s');

        echo "<h2>Debug getUjianMendatangFixed Method</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";
        echo "<p><strong>Now:</strong> $now</p>";

        // Panggil method dengan debug output langsung
        $result = $this->getUjianMendatangFixedWithDebug($userId, $now);

        echo "<h3>Final Result:</h3>";
        echo "<p><strong>Count:</strong> " . count($result) . "</p>";

        if (empty($result)) {
            echo "<p style='color: red;'>❌ KOSONG!</p>";
        } else {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Index</th><th>ID</th><th>Judul</th><th>Mulai</th><th>Status Peserta</th></tr>";
            foreach ($result as $idx => $ujian) {
                echo "<tr>";
                echo "<td>$idx</td>";
                echo "<td>{$ujian['id']}</td>";
                echo "<td>{$ujian['judul']}</td>";
                echo "<td>{$ujian['mulai']}</td>";
                echo "<td>" . ($ujian['status_peserta'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    private function getUjianMendatangClean($userId, $now)
    {
        // DEBUG: Log untuk tracking masalah
        error_log("=== getUjianMendatangClean START ===");
        error_log("User ID: $userId, Now: $now");

        // 1. Ambil ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        error_log("Ujian ID Array: " . json_encode($ujianIdArray));

        if (empty($ujianIdArray)) {
            error_log("Ujian ID Array kosong, return []");
            return [];
        }

        // 2. Status map
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }
        error_log("Status Map: " . json_encode($statusMap));

        // 3. Ambil data ujian
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();
        error_log("Ujian List Count: " . count($ujianList));

        // 4. Process kategori
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($ujianList as &$ujian) {
            if (!empty($ujian['kategori_data'])) {
                $ujian['nama_kategori'] = 'Multiple Kategori';
            } else {
                $kategori = $kategoriModel->find($ujian['kategori_id']);
                $ujian['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
            }
        }

        // 5. Filter ujian mendatang
        $processedIds = [];
        $ujian_mendatang = [];

        foreach ($ujianList as $ujian) {
            $ujianId = $ujian['id'];

            error_log("Processing Ujian ID: $ujianId, Judul: {$ujian['judul']}");

            // Skip jika sudah diproses
            if (in_array($ujianId, $processedIds)) {
                error_log("Skip - sudah diproses");
                continue;
            }
            $processedIds[] = $ujianId;

            $status = $statusMap[$ujianId] ?? 'belum_mulai';
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);

            error_log("Status: $status, Waktu Mulai: {$ujian['mulai']} ($waktuMulai), Waktu Sekarang: $now ($waktuSekarang)");
            error_log("Kondisi: waktuMulai > waktuSekarang = " . ($waktuMulai > $waktuSekarang ? 'TRUE' : 'FALSE'));
            error_log("Kondisi: status !== 'selesai' = " . ($status !== 'selesai' ? 'TRUE' : 'FALSE'));

            // Filter condition - SAMA PERSIS dengan debug method
            if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                $ujian['status_peserta'] = 'belum_dimulai';
                $ujian_mendatang[] = $ujian;
                error_log("✅ DITAMBAHKAN ke ujian_mendatang");
            } else {
                error_log("❌ TIDAK DITAMBAHKAN");
            }
        }

        error_log("Final ujian_mendatang count: " . count($ujian_mendatang));
        error_log("=== getUjianMendatangClean END ===");

        return $ujian_mendatang;
    }

    private function getUjianMendatangExact($userId, $now)
    {
        // 1. Ambil ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        if (empty($ujianIdArray)) {
            return [];
        }

        // 2. Status map
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
        }

        // 3. Ambil data ujian
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();

        // 4. Process kategori (simplified) - PERBAIKAN: Hapus reference &
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($ujianList as $key => $ujian) {
            if (!empty($ujian['kategori_data'])) {
                $ujianList[$key]['nama_kategori'] = 'Multiple Kategori';
            } else {
                $kategori = $kategoriModel->find($ujian['kategori_id']);
                $ujianList[$key]['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
            }
        }

        // 5. Filter ujian mendatang - EXACT SAME LOGIC
        $processedIds = [];
        $ujian_mendatang = [];

        foreach ($ujianList as $ujian) {
            $ujianId = $ujian['id'];

            // Skip jika sudah diproses
            if (in_array($ujianId, $processedIds)) {
                continue;
            }
            $processedIds[] = $ujianId;

            $status = $statusMap[$ujianId] ?? 'belum_mulai';
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);

            // Filter condition - EXACT SAME
            if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                $ujian['status_peserta'] = 'belum_dimulai';
                $ujian_mendatang[] = $ujian;
            }
        }

        return $ujian_mendatang;
    }

    private function getUjianMendatangFixedWithDebug($userId, $now)
    {
        echo "<h3>Step by Step Debug:</h3>";

        // 1. Ambil ujian yang peserta terdaftar
        echo "<h4>1. Ambil ujian_peserta:</h4>";
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        echo "<p><strong>Ujian ID Array:</strong> [" . implode(', ', $ujianIdArray) . "]</p>";

        if (empty($ujianIdArray)) {
            echo "<p style='color: red;'>❌ Ujian ID Array kosong!</p>";
            return [];
        }

        // 2. Status map
        echo "<h4>2. Status Map:</h4>";
        $statusMap = [];
        foreach ($ujianPeserta as $up) {
            $statusMap[$up['ujian_id']] = $up['status'];
            echo "<p>Ujian ID {$up['ujian_id']}: {$up['status']}</p>";
        }

        // 3. Ambil data ujian
        echo "<h4>3. Ambil data ujian:</h4>";
        $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();
        echo "<p><strong>Ujian List Count:</strong> " . count($ujianList) . "</p>";

        foreach ($ujianList as $index => $ujian) {
            echo "<p>[$index] ID: {$ujian['id']}, Judul: {$ujian['judul']}, Mulai: {$ujian['mulai']}</p>";
        }

        // DEBUG: Cek urutan array
        echo "<p><strong>Array Keys:</strong> " . implode(', ', array_keys($ujianList)) . "</p>";
        echo "<p><strong>Ujian IDs in order:</strong> " . implode(', ', array_column($ujianList, 'id')) . "</p>";

        // 4. Process kategori (simplified) - PERBAIKAN: Hapus reference &
        echo "<h4>4. Process kategori:</h4>";
        $kategoriModel = new \App\Models\KategoriModel();
        foreach ($ujianList as $key => $ujian) {
            if (!empty($ujian['kategori_data'])) {
                $ujianList[$key]['nama_kategori'] = 'Multiple Kategori';
            } else {
                $kategori = $kategoriModel->find($ujian['kategori_id']);
                $ujianList[$key]['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
            }
            echo "<p>Ujian {$ujian['id']}: {$ujianList[$key]['nama_kategori']}</p>";
        }

        // 5. Filter ujian mendatang
        echo "<h4>5. Filter ujian mendatang:</h4>";
        echo "<p><strong>DEBUG: Akan memproses " . count($ujianList) . " ujian</strong></p>";

        $processedIds = [];
        $ujian_mendatang = [];
        $loopCount = 0;

        foreach ($ujianList as $index => $ujian) {
            $loopCount++;
            $ujianId = $ujian['id'];

            echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
            echo "<h5>Loop #$loopCount - Index [$index]: {$ujian['judul']} (ID: $ujianId)</h5>";

            // Skip jika sudah diproses
            if (in_array($ujianId, $processedIds)) {
                echo "<p style='color: orange;'>⚠️ Skip - sudah diproses</p>";
                echo "</div>";
                continue;
            }
            $processedIds[] = $ujianId;

            $status = $statusMap[$ujianId] ?? 'belum_mulai';
            $waktuMulai = strtotime($ujian['mulai']);
            $waktuSekarang = strtotime($now);

            echo "<p><strong>Status:</strong> $status</p>";
            echo "<p><strong>Waktu Mulai:</strong> {$ujian['mulai']} (timestamp: $waktuMulai)</p>";
            echo "<p><strong>Waktu Sekarang:</strong> $now (timestamp: $waktuSekarang)</p>";
            echo "<p><strong>Kondisi 1:</strong> waktuMulai > waktuSekarang = " . ($waktuMulai > $waktuSekarang ? 'TRUE' : 'FALSE') . "</p>";
            echo "<p><strong>Kondisi 2:</strong> status !== 'selesai' = " . ($status !== 'selesai' ? 'TRUE' : 'FALSE') . "</p>";

            // Filter condition
            if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                $ujian['status_peserta'] = 'belum_dimulai';
                $ujian_mendatang[] = $ujian;
                echo "<p style='color: green; font-weight: bold;'>✅ DITAMBAHKAN ke ujian_mendatang</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ TIDAK DITAMBAHKAN</p>";
                if ($waktuMulai <= $waktuSekarang) {
                    echo "<p style='color: red;'>Alasan: Waktu sudah lewat</p>";
                }
                if ($status === 'selesai') {
                    echo "<p style='color: red;'>Alasan: Status sudah selesai</p>";
                }
            }
            echo "</div>";
        }

        echo "<p><strong>Final Count:</strong> " . count($ujian_mendatang) . "</p>";

        return $ujian_mendatang;
    }

    public function debugDashboardData()
    {
        if (ENVIRONMENT !== 'development') {
            return redirect()->to('peserta/dashboard');
        }

        date_default_timezone_set('Asia/Jakarta');
        $userId = session('id');
        $now = date('Y-m-d H:i:s');

        echo "<h2>Debug Dashboard Data</h2>";
        echo "<p><strong>User ID:</strong> $userId</p>";
        echo "<p><strong>Now:</strong> $now</p>";

        // PERBAIKAN: Gunakan logic yang SAMA PERSIS dengan dashboard
        // 1. Ambil ujian yang peserta terdaftar
        $ujianPeserta = $this->ujianPesertaModel->where('peserta_id', $userId)->findAll();
        $ujianIdArray = array_column($ujianPeserta, 'ujian_id');

        $ujian_mendatang = [];
        if (!empty($ujianIdArray)) {
            // 2. Status map
            $statusMap = [];
            foreach ($ujianPeserta as $up) {
                $statusMap[$up['ujian_id']] = $up['status'];
            }

            // 3. Ambil data ujian
            $ujianList = $this->ujianModel->whereIn('id', $ujianIdArray)->findAll();

            // 4. Process kategori
            $kategoriModel = new \App\Models\KategoriModel();
            foreach ($ujianList as $key => $ujian) {
                if (!empty($ujian['kategori_data'])) {
                    $kategoriData = json_decode($ujian['kategori_data'], true);
                    if ($kategoriData && is_array($kategoriData)) {
                        $namaKategoriList = [];
                        foreach ($kategoriData as $kd) {
                            $kategori = $kategoriModel->find($kd['kategori_id']);
                            if ($kategori) {
                                $namaKategoriList[] = $kategori['nama_kategori'];
                            }
                        }
                        $ujianList[$key]['nama_kategori'] = implode(', ', $namaKategoriList);
                        $ujianList[$key]['nama_kategori_multiple'] = implode(', ', $namaKategoriList);
                    } else {
                        $ujianList[$key]['nama_kategori'] = 'Multiple Kategori';
                    }
                } else {
                    // Single kategori
                    $kategori = $kategoriModel->find($ujian['kategori_id']);
                    $ujianList[$key]['nama_kategori'] = $kategori ? $kategori['nama_kategori'] : 'Umum';
                }
            }

            // 5. Filter ujian mendatang
            $processedIds = [];

            foreach ($ujianList as $ujian) {
                $ujianId = $ujian['id'];

                // Skip jika sudah diproses
                if (in_array($ujianId, $processedIds)) {
                    continue;
                }
                $processedIds[] = $ujianId;

                $status = $statusMap[$ujianId] ?? 'belum_mulai';
                $waktuMulai = strtotime($ujian['mulai']);
                $waktuSekarang = strtotime($now);

                // Filter condition
                if ($waktuMulai > $waktuSekarang && $status !== 'selesai') {
                    $ujian['status_peserta'] = 'belum_dimulai';
                    $ujian_mendatang[] = $ujian;
                }
            }
        }

        $ujian_aktif = $this->forceGetUjianAktif($now);

        echo "<h3>Data yang dikirim ke view:</h3>";
        echo "<h4>Ujian Mendatang (Count: " . count($ujian_mendatang) . "):</h4>";

        if (empty($ujian_mendatang)) {
            echo "<p style='color: red;'>❌ KOSONG!</p>";
        } else {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Index</th><th>ID</th><th>Judul</th><th>Mulai</th><th>Kategori</th></tr>";
            foreach ($ujian_mendatang as $idx => $ujian) {
                echo "<tr>";
                echo "<td>$idx</td>";
                echo "<td>{$ujian['id']}</td>";
                echo "<td>{$ujian['judul']}</td>";
                echo "<td>{$ujian['mulai']}</td>";
                echo "<td>" . ($ujian['nama_kategori'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        echo "<h4>Ujian Aktif (Count: " . count($ujian_aktif) . "):</h4>";

        if (empty($ujian_aktif)) {
            echo "<p style='color: blue;'>ℹ️ Kosong (normal jika tidak ada ujian aktif)</p>";
        } else {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Index</th><th>ID</th><th>Judul</th><th>Mulai</th><th>Kategori</th></tr>";
            foreach ($ujian_aktif as $idx => $ujian) {
                echo "<tr>";
                echo "<td>$idx</td>";
                echo "<td>{$ujian['id']}</td>";
                echo "<td>{$ujian['judul']}</td>";
                echo "<td>{$ujian['mulai']}</td>";
                echo "<td>" . ($ujian['nama_kategori'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        echo "<p><a href='" . base_url('peserta/dashboard') . "'>Kembali ke Dashboard</a></p>";
    }

    private function getStatistikHasil($userId)
    {
        // Ambil hasil ujian
        $hasil_ujian = $this->hasilUjianModel->getHasilByPeserta($userId);

        // Hitung rata-rata pencapaian untuk setiap hasil ujian
        foreach ($hasil_ujian as &$hasil) {
            $hasil['rata_rata_pencapaian'] = $this->hitungRataRataPencapaian($hasil);
            $hasil['is_lulus'] = $this->tentukanKelulusan($hasil);
        }

        // Hitung statistik seperti di halaman hasil
        $total_ujian = count($hasil_ujian);
        $total_lulus = 0;
        $total_pencapaian = 0;

        foreach ($hasil_ujian as $hasil) {
            if ($hasil['is_lulus']) {
                $total_lulus++;
            }
            $total_pencapaian += $hasil['rata_rata_pencapaian'];
        }

        $rata_rata_pencapaian = $total_ujian > 0 ? round($total_pencapaian / $total_ujian, 0) : 0;

        return [
            'total_ujian' => $total_ujian,
            'lulus' => $total_lulus,
            'rata_rata' => $rata_rata_pencapaian,
            'hasil_ujian' => $hasil_ujian // Untuk keperluan lain jika diperlukan
        ];
    }
}

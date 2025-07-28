<?php
namespace App\Controllers\Pembimbing;

use App\Controllers\BaseController;
use App\Models\UjianModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\UjianPesertaModel;
use App\Models\SoalModel;

class Ujian extends BaseController
{
    protected $ujianModel;
    protected $kategoriModel;
    protected $soalModel;

    public function __construct()
    {
        $this->ujianModel = new UjianModel();
        $this->kategoriModel = new KategoriModel();
        $this->soalModel = new SoalModel();
    }

    public function index()
    {
        $pembimbingId = session('id'); // ✅ Perbaikan

        $ujianList = $this->ujianModel
            ->where('pembimbing_id', $pembimbingId)
            ->join('kategori_soal', 'kategori_soal.id = ujian.kategori_id')
            ->select('ujian.*, kategori_soal.nama_kategori')
            ->findAll();

        // Ensure kategori_data exists for all ujian (backward compatibility)
        foreach ($ujianList as &$ujian) {
            if (!isset($ujian['kategori_data']) || empty($ujian['kategori_data'])) {
                // Create default kategori_data for old ujian
                $ujian['kategori_data'] = json_encode([
                    [
                        'kategori_id' => $ujian['kategori_id'],
                        'jumlah_soal' => $ujian['jumlah_soal'],
                        'passing_grade' => $ujian['passing_grade'],
                        'waktu_menit' => $ujian['waktu_menit']
                    ]
                ]);
            }
        }

        $data['ujian'] = $ujianList;
        $data['kategori'] = $this->kategoriModel->findAll(); // untuk dropdown kategori

        return view('pembimbing/ujian/index', $data);
    }

    public function create()
    {
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('pembimbing/ujian/create', $data);
    }

    public function save()
    {
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $jamMulai = $this->request->getPost('jam_mulai');
        $mulai = $tanggalMulai . ' ' . $jamMulai . ':00';

        // Pastikan waktu menggunakan timezone server
        $mulaiTimestamp = strtotime($mulai);
        $mulai = format_server_time($mulaiTimestamp, 'Y-m-d H:i:s');

        $kategoriIds = $this->request->getPost('kategori_id');
        $jumlahSoals = $this->request->getPost('jumlah_soal');
        $passingGrades = $this->request->getPost('passing_grade');
        $waktuMinits = $this->request->getPost('waktu_menit');

        // Debug: log received data
        log_message('debug', 'Kategori IDs: ' . print_r($kategoriIds, true));
        log_message('debug', 'Jumlah Soals: ' . print_r($jumlahSoals, true));
        log_message('debug', 'Passing Grades: ' . print_r($passingGrades, true));
        log_message('debug', 'Waktu Minits: ' . print_r($waktuMinits, true));

        // Validate that we have at least one mata pelajaran
        if (empty($kategoriIds) || empty($jumlahSoals) || empty($waktuMinits)) {
            return redirect()->back()->with('error', 'Minimal harus ada satu mata pelajaran dengan waktu ujian.');
        }

        // Validate array lengths match
        if (count($kategoriIds) !== count($jumlahSoals) || count($kategoriIds) !== count($waktuMinits)) {
            return redirect()->back()->with('error', 'Data mata pelajaran tidak lengkap.');
        }

        // Validate jumlah soal for each kategori
        $pembimbingId = session('id');
        for ($i = 0; $i < count($kategoriIds); $i++) {
            $kategoriId = $kategoriIds[$i];
            $jumlahSoalDiminta = $jumlahSoals[$i];

            $jumlahSoalTersedia = $this->soalModel
                ->where('kategori_id', $kategoriId)
                ->where('pembimbing_id', $pembimbingId)
                ->countAllResults();

            if ($jumlahSoalDiminta > $jumlahSoalTersedia) {
                return redirect()->back()->with('error', 'Jumlah soal untuk mata pelajaran tidak mencukupi.');
            }
        }

        // Create ujian data with multiple categories
        $kategoriData = [];
        $totalSoal = 0;
        $totalWaktu = 0;
        for ($i = 0; $i < count($kategoriIds); $i++) {
            $kategoriData[] = [
                'kategori_id' => $kategoriIds[$i],
                'jumlah_soal' => $jumlahSoals[$i],
                'passing_grade' => $passingGrades[$i],
                'waktu_menit' => $waktuMinits[$i]
            ];
            $totalSoal += $jumlahSoals[$i];
            $totalWaktu += $waktuMinits[$i];
        }

        $ujianId = $this->ujianModel->insert([
            'judul'           => $this->request->getPost('judul'),
            'kategori_id'     => $kategoriIds[0], // Primary category for compatibility
            'pembimbing_id'   => $pembimbingId,
            'jumlah_soal'     => $totalSoal,
            'waktu_menit'     => $totalWaktu, // Total waktu dari semua mata pelajaran
            'toleransi_menit' => $this->request->getPost('toleransi_menit'),
            'acak_soal'       => $this->request->getPost('acak_soal'),
            'passing_grade'   => $this->request->getPost('passing_grade_total'), // Use passing grade total
            'mulai'           => $mulai,
            'kategori_data'   => json_encode($kategoriData), // Store multiple categories with individual time
            'status'          => 'draft'
        ]);

        // PERBAIKAN: Auto-register semua peserta aktif ke ujian baru
        $userModel = new \App\Models\UserModel();
        $pesertaAktif = $userModel->where('role', 'peserta')->where('aktif', 1)->findAll();

        $ujianPesertaModel = new \App\Models\UjianPesertaModel();
        $totalPesertaTerdaftar = 0;

        foreach ($pesertaAktif as $peserta) {
            // Cek apakah sudah terdaftar (untuk menghindari duplikasi)
            $sudahTerdaftar = $ujianPesertaModel
                ->where('ujian_id', $ujianId)
                ->where('peserta_id', $peserta['id'])
                ->first();

            if (!$sudahTerdaftar) {
                $ujianPesertaModel->insert([
                    'ujian_id' => $ujianId,
                    'peserta_id' => $peserta['id'],
                    'status' => 'belum_mulai'
                ]);
                $totalPesertaTerdaftar++;
            }
        }

        return redirect()->to('pembimbing/ujian')->with('success', 'Ujian berhasil dibuat dengan ' . count($kategoriIds) . ' mata pelajaran! ' . $totalPesertaTerdaftar . ' peserta aktif telah didaftarkan otomatis.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $ujian = $this->ujianModel->find($id);

        if (!$ujian) {
            return redirect()->back()->with('error', 'Ujian tidak ditemukan.');
        }

        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $jamMulai = $this->request->getPost('jam_mulai');
        $mulai = $tanggalMulai . ' ' . $jamMulai . ':00';

        $kategoriIds = $this->request->getPost('kategori_id');
        $jumlahSoals = $this->request->getPost('jumlah_soal');
        $passingGrades = $this->request->getPost('passing_grade');
        $waktuMinits = $this->request->getPost('waktu_menit');

        // Validate arrays
        if (empty($kategoriIds) || empty($jumlahSoals) || empty($passingGrades) || empty($waktuMinits)) {
            return redirect()->back()->with('error', 'Minimal harus ada satu mata pelajaran dengan data lengkap.');
        }

        if (count($kategoriIds) !== count($jumlahSoals) || count($kategoriIds) !== count($passingGrades) || count($kategoriIds) !== count($waktuMinits)) {
            return redirect()->back()->with('error', 'Data mata pelajaran tidak lengkap.');
        }

        // Create kategori data
        $kategoriData = [];
        $totalSoal = 0;
        $totalWaktu = 0;
        for ($i = 0; $i < count($kategoriIds); $i++) {
            $kategoriData[] = [
                'kategori_id' => $kategoriIds[$i],
                'jumlah_soal' => $jumlahSoals[$i],
                'passing_grade' => $passingGrades[$i],
                'waktu_menit' => $waktuMinits[$i]
            ];
            $totalSoal += $jumlahSoals[$i];
            $totalWaktu += $waktuMinits[$i];
        }

        $this->ujianModel->update($id, [
            'judul'           => $this->request->getPost('judul'),
            'kategori_id'     => $kategoriIds[0],
            'jumlah_soal'     => $totalSoal,
            'waktu_menit'     => $totalWaktu,
            'toleransi_menit' => $this->request->getPost('toleransi_menit'),
            'acak_soal'       => $this->request->getPost('acak_soal'),
            'passing_grade'   => $this->request->getPost('passing_grade_total'), // Use passing grade total
            'mulai'           => $mulai,
            'kategori_data'   => json_encode($kategoriData)
        ]);

        return redirect()->to('pembimbing/ujian')->with('success', 'Ujian berhasil diperbarui dengan ' . count($kategoriIds) . ' mata pelajaran!');
    }

    public function refreshToken($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $ujian = $this->ujianModel->find($id);
        if (!$ujian) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ujian tidak ditemukan']);
        }

        // Generate token baru dengan timestamp saat ini
        $currentTime = date('Y-m-d H:i:s');
        $newToken = strtoupper(substr(md5($id . $currentTime . rand(1000, 9999)), 0, 5));

        // Update dengan field yang pasti ada (judul tidak berubah tapi tetap update)
        $updateResult = $this->ujianModel->update($id, [
            'judul' => $ujian['judul'], // Update dengan nilai yang sama untuk trigger update
            'updated_at' => $currentTime
        ]);

        if ($updateResult === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal update database']);
        }

        return $this->response->setJSON([
            'success' => true,
            'newToken' => $newToken,
            'message' => 'Token berhasil di-refresh'
        ]);
    }
        public function edit($id)
    {
        $data['ujian'] = $this->ujianModel->find($id);
        $data['kategori'] = $this->kategoriModel->findAll();
        return view('pembimbing/ujian/edit', $data);
    }



    public function delete($id)
    {
        $ujian = $this->ujianModel->find($id);
        if ($ujian) {
            $this->ujianModel->delete($id);
            return redirect()->back()->with('success', 'Ujian "' . $ujian['judul'] . '" berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Ujian tidak ditemukan.');
    }

    public function peserta($ujianId)
{
    $ujian = $this->ujianModel
        ->join('kategori_soal', 'kategori_soal.id = ujian.kategori_id')
        ->select('ujian.*, kategori_soal.nama_kategori')
        ->where('ujian.id', $ujianId)
        ->first();

    if (!$ujian) {
        return redirect()->back()->with('error', 'Ujian tidak ditemukan');
    }

    $userModel = new UserModel();
    $pesertaModel = new UjianPesertaModel();

    $data['ujian'] = $ujian;
    $data['peserta'] = $userModel->where('role', 'peserta')->findAll();

    $peserta_terpilih = $pesertaModel
        ->where('ujian_id', $ujianId)
        ->findAll();

    $data['peserta_terpilih'] = array_column($peserta_terpilih, 'peserta_id');

    return view('pembimbing/ujian/peserta', $data);
}

public function savePeserta($ujianId)
{
    $pesertaIds = $this->request->getPost('peserta_id') ?? [];

    $pesertaModel = new UjianPesertaModel();
    $pesertaModel->where('ujian_id', $ujianId)->delete();

    foreach ($pesertaIds as $pid) {
        $pesertaModel->insert([
            'ujian_id' => $ujianId,
            'peserta_id' => $pid,
        ]);
    }

    return redirect()->to('pembimbing/ujian')->with('success', 'Peserta berhasil diperbarui.');
}

public function copyPeserta($ujianId)
{
    $ujianSumber = $this->request->getPost('ujian_sumber');

    if (!$ujianSumber) {
        return redirect()->back()->with('error', 'Pilih ujian sumber terlebih dahulu.');
    }

    $pesertaModel = new UjianPesertaModel();

    // Get peserta dari ujian sumber
    $pesertaSumber = $pesertaModel->where('ujian_id', $ujianSumber)->findAll();

    if (empty($pesertaSumber)) {
        return redirect()->back()->with('error', 'Tidak ada peserta di ujian sumber.');
    }

    // Hapus peserta lama di ujian tujuan
    $pesertaModel->where('ujian_id', $ujianId)->delete();

    // Copy peserta dari ujian sumber
    foreach ($pesertaSumber as $peserta) {
        $pesertaModel->insert([
            'ujian_id' => $ujianId,
            'peserta_id' => $peserta['peserta_id'],
        ]);
    }

    return redirect()->back()->with('success', 'Peserta berhasil disalin dari ujian sumber (' . count($pesertaSumber) . ' peserta).');
}

public function getJumlahSoal()
{
    $kategoriId = $this->request->getPost('kategori_id');
    $pembimbingId = session('id');

    $jumlahSoal = $this->soalModel
        ->where('kategori_id', $kategoriId)
        ->where('pembimbing_id', $pembimbingId)
        ->countAllResults();

    return $this->response->setJSON(['jumlah_soal' => $jumlahSoal]);
}

}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SoalModel;
use App\Models\KategoriModel;
use App\Models\UserModel;
use App\Models\AnalisaSoalModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Soal extends BaseController
{
    protected $soalModel;
    protected $kategoriModel;
    protected $userModel;
    protected $analisaSoalModel;

    public function __construct()
    {
        $this->soalModel = new SoalModel();
        $this->kategoriModel = new KategoriModel();
        $this->userModel = new UserModel();
        $this->analisaSoalModel = new AnalisaSoalModel();
    }

    public function index()
    {
        // Ambil parameter per page dari request, default 10
        $perPage = (int)($this->request->getVar('per_page') ?? 10);

        // Validasi per page hanya boleh nilai tertentu
        $allowedPerPage = [10, 25, 50, 100, 500];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $currentPage = $this->request->getVar('page') ?? 1;
        $search = $this->request->getVar('search') ?? '';
        $kategoriFilter = $this->request->getVar('kategori') ?? '';

        // Query builder dengan filter
        $builder = $this->soalModel
            ->select('soal.*, kategori_soal.nama_kategori as kategori, users.nama_lengkap as pembimbing')
            ->join('kategori_soal', 'kategori_soal.id = soal.kategori_id')
            ->join('users', 'users.id = soal.pembimbing_id');

        // Filter pencarian
        if (!empty($search)) {
            $builder->groupStart()
                ->like('soal.pertanyaan', $search)
                ->orLike('kategori_soal.nama_kategori', $search)
                ->orLike('users.nama_lengkap', $search)
                ->groupEnd();
        }

        // Filter kategori
        if (!empty($kategoriFilter)) {
            $builder->where('soal.kategori_id', $kategoriFilter);
        }

        // Ambil data dengan pagination
        $data['soal'] = $builder->paginate($perPage, 'default', $currentPage);
        $data['pager'] = $this->soalModel->pager;
        $data['currentPage'] = $currentPage;
        $data['perPage'] = $perPage;
        $data['total'] = $builder->countAllResults(false);
        $data['allowedPerPage'] = $allowedPerPage;
        $data['search'] = $search;
        $data['kategoriFilter'] = $kategoriFilter;

        // Ambil data untuk dropdown
        $data['kategori'] = $this->kategoriModel->findAll();
        $data['pembimbing'] = $this->userModel->where('role', 'pembimbing')->findAll();

        // Ambil analisa soal untuk soal yang ditampilkan
        $soalIds = array_column($data['soal'], 'id');
        $analisaData = $this->analisaSoalModel->getAnalisaSoal($soalIds);

        // Convert analisa ke format yang mudah diakses
        $data['analisa'] = [];
        foreach ($analisaData as $analisa) {
            $data['analisa'][$analisa['soal_id']] = $analisa;
        }

        return view('admin/soal/index', $data);
    }

    public function create()
    {
        $data['kategori'] = $this->kategoriModel->findAll();
        $data['pembimbing'] = $this->userModel->where('role', 'pembimbing')->findAll();

        return view('admin/soal/create', $data);
    }

    public function save()
    {
        $this->soalModel->save($this->request->getPost());
        return redirect()->to('admin/soal')->with('success', 'Soal berhasil ditambahkan.');
    }

    public function update()
    {
        $data = $this->request->getPost();
        $id = $data['id'];

        // Ambil data soal untuk pesan yang lebih spesifik
        $soal = $this->soalModel->find($id);
        if (!$soal) {
            return redirect()->to('admin/soal')->with('error', 'Soal tidak ditemukan.');
        }

        // Update soal
        $this->soalModel->update($id, $data);

        // Redirect ke halaman soal dengan pesan sukses
        return redirect()->to('admin/soal')->with('success', 'Soal berhasil diperbarui!');
    }

    public function delete($id)
    {
        $this->soalModel->delete($id);
        return redirect()->back()->with('success', 'Soal berhasil dihapus.');
    }

    public function import()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak dapat diupload.');
        }

        // Validasi ekstensi file
        $allowedExtensions = ['xlsx', 'xls'];
        $fileExtension = $file->getClientExtension();
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            return redirect()->back()->with('error', 'File harus berformat .xlsx atau .xls');
        }

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'File Excel tidak dapat dibaca. Pastikan file tidak corrupt.');
        }

        // Validasi header
        if (empty($sheet) || count($sheet) < 2) {
            return redirect()->back()->with('error', 'File Excel kosong atau tidak memiliki data.');
        }

        $expectedHeaders = [
            'Pertanyaan', 'Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D',
            'Pilihan E', 'Kunci Jawaban', 'Bobot', 'Kategori', 'Pembimbing'
        ];

        $actualHeaders = array_slice($sheet[0], 0, 10);
        for ($i = 0; $i < count($expectedHeaders); $i++) {
            if (!isset($actualHeaders[$i]) || trim($actualHeaders[$i]) !== $expectedHeaders[$i]) {
                return redirect()->back()->with('error', 'Format header tidak sesuai template. Silakan download template yang benar.');
            }
        }

        $kategoriModel = new KategoriModel();
        $userModel = new UserModel();

        $berhasil = 0;
        $gagal = 0;
        $errorDetails = [];

        foreach (array_slice($sheet, 1) as $rowIndex => $row) {
            $lineNumber = $rowIndex + 2; // +2 karena array dimulai dari 0 dan ada header

            // Skip baris kosong
            if (empty(trim($row[0]))) continue;

            // Validasi data wajib
            $requiredFields = [0, 1, 2, 3, 4, 6, 7, 8, 9]; // Index kolom yang wajib diisi
            $fieldNames = ['Pertanyaan', 'Pilihan A', 'Pilihan B', 'Pilihan C', 'Pilihan D', 'Kunci Jawaban', 'Bobot', 'Kategori', 'Pembimbing'];

            $missingFields = [];
            foreach ($requiredFields as $index => $fieldIndex) {
                if (empty(trim($row[$fieldIndex]))) {
                    $missingFields[] = $fieldNames[$index];
                }
            }

            if (!empty($missingFields)) {
                $gagal++;
                $errorDetails[] = "Baris $lineNumber: Field kosong - " . implode(', ', $missingFields);
                continue;
            }

            // Validasi kunci jawaban
            $kunciJawaban = strtoupper(trim($row[6]));
            if (!in_array($kunciJawaban, ['A', 'B', 'C', 'D', 'E'])) {
                $gagal++;
                $errorDetails[] = "Baris $lineNumber: Kunci jawaban harus A, B, C, D, atau E";
                continue;
            }

            // Validasi bobot
            $bobot = trim($row[7]);
            if (!is_numeric($bobot) || $bobot < 1) {
                $gagal++;
                $errorDetails[] = "Baris $lineNumber: Bobot harus berupa angka minimal 1";
                continue;
            }

            // Cari kategori berdasarkan nama
            $namaKategori = trim($row[8]);
            $kategori = $kategoriModel->where('nama_kategori', $namaKategori)->first();
            if (!$kategori) {
                $gagal++;
                $errorDetails[] = "Baris $lineNumber: Kategori '$namaKategori' tidak ditemukan";
                continue;
            }

            // Cari pembimbing berdasarkan nama
            $namaPembimbing = trim($row[9]);
            $pembimbing = $userModel
                ->where('role', 'pembimbing')
                ->where('nama_lengkap', $namaPembimbing)
                ->first();
            if (!$pembimbing) {
                $gagal++;
                $errorDetails[] = "Baris $lineNumber: Pembimbing '$namaPembimbing' tidak ditemukan";
                continue;
            }

            // Insert data soal
            try {
                $this->soalModel->insert([
                    'pertanyaan' => trim($row[0]),
                    'pilihan_a' => trim($row[1]),
                    'pilihan_b' => trim($row[2]),
                    'pilihan_c' => trim($row[3]),
                    'pilihan_d' => trim($row[4]),
                    'pilihan_e' => trim($row[5] ?? ''), // Pilihan E opsional
                    'kunci_jawaban' => $kunciJawaban,
                    'bobot' => (int)$bobot,
                    'kategori_id' => $kategori['id'],
                    'pembimbing_id' => $pembimbing['id'],
                ]);
                $berhasil++;
            } catch (\Exception $e) {
                $gagal++;
                $errorDetails[] = "Baris $lineNumber: Gagal menyimpan data - " . $e->getMessage();
            }
        }

        // Buat pesan hasil import
        $message = "Import selesai! Berhasil: $berhasil soal | Gagal: $gagal soal";

        if ($gagal > 0 && count($errorDetails) <= 10) {
            // Tampilkan detail error jika tidak terlalu banyak
            $message .= "\n\nDetail Error:\n" . implode("\n", array_slice($errorDetails, 0, 10));
            if (count($errorDetails) > 10) {
                $message .= "\n... dan " . (count($errorDetails) - 10) . " error lainnya";
            }
        }

        $type = $gagal > 0 ? 'warning' : 'success';
        return redirect()->back()->with($type, $message);
    }

    public function downloadTemplate()
    {
        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul sheet
        $sheet->setTitle('Template Import Soal');

        // Header kolom
        $headers = [
            'A1' => 'Pertanyaan',
            'B1' => 'Pilihan A',
            'C1' => 'Pilihan B',
            'D1' => 'Pilihan C',
            'E1' => 'Pilihan D',
            'F1' => 'Pilihan E',
            'G1' => 'Kunci Jawaban',
            'H1' => 'Bobot',
            'I1' => 'Kategori',
            'J1' => 'Pembimbing'
        ];

        // Set header
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Ambil data kategori dan pembimbing untuk contoh
        $kategoriList = $this->kategoriModel->findAll();
        $pembimbingList = $this->userModel->where('role', 'pembimbing')->findAll();

        // Contoh data
        $contohData = [
            [
                'Apa ibu kota Indonesia?',
                'Jakarta',
                'Bandung',
                'Surabaya',
                'Medan',
                'Yogyakarta',
                'A',
                '1',
                !empty($kategoriList) ? $kategoriList[0]['nama_kategori'] : 'Geografi',
                !empty($pembimbingList) ? $pembimbingList[0]['nama_lengkap'] : 'Nama Pembimbing'
            ],
            [
                'Siapa presiden pertama Indonesia?',
                'Soekarno',
                'Soeharto',
                'Habibie',
                'Megawati',
                'SBY',
                'A',
                '1',
                !empty($kategoriList) ? $kategoriList[0]['nama_kategori'] : 'Sejarah',
                !empty($pembimbingList) ? $pembimbingList[0]['nama_lengkap'] : 'Nama Pembimbing'
            ]
        ];

        // Isi contoh data
        $row = 2;
        foreach ($contohData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }

        // Style contoh data
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                'wrapText' => true
            ]
        ];
        $sheet->getStyle('A2:J' . ($row - 1))->applyFromArray($dataStyle);

        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set minimum width untuk kolom pertanyaan dan pilihan
        $sheet->getColumnDimension('A')->setWidth(40); // Pertanyaan
        $sheet->getColumnDimension('B')->setWidth(25); // Pilihan A
        $sheet->getColumnDimension('C')->setWidth(25); // Pilihan B
        $sheet->getColumnDimension('D')->setWidth(25); // Pilihan C
        $sheet->getColumnDimension('E')->setWidth(25); // Pilihan D
        $sheet->getColumnDimension('F')->setWidth(25); // Pilihan E

        // Set row height
        $sheet->getRowDimension(1)->setRowHeight(25);
        for ($i = 2; $i < $row; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(60);
        }

        // Tambahkan sheet instruksi
        $instructionSheet = $spreadsheet->createSheet();
        $instructionSheet->setTitle('Instruksi');

        $instructions = [
            ['INSTRUKSI IMPORT SOAL'],
            [''],
            ['1. Format File:'],
            ['   - File harus berformat .xlsx atau .xls'],
            ['   - Jangan mengubah urutan kolom'],
            ['   - Jangan mengubah nama header'],
            [''],
            ['2. Pengisian Data:'],
            ['   - Pertanyaan: Wajib diisi'],
            ['   - Pilihan A-D: Wajib diisi'],
            ['   - Pilihan E: Opsional (boleh kosong)'],
            ['   - Kunci Jawaban: Harus A, B, C, D, atau E'],
            ['   - Bobot: Angka minimal 1'],
            ['   - Kategori: Nama kategori yang sudah ada di sistem'],
            ['   - Pembimbing: Nama lengkap pembimbing yang sudah ada di sistem'],
            [''],
            ['3. Kategori yang Tersedia:']
        ];

        // Tambahkan daftar kategori
        foreach ($kategoriList as $kategori) {
            $instructions[] = ['   - ' . $kategori['nama_kategori']];
        }

        $instructions[] = [''];
        $instructions[] = ['4. Pembimbing yang Tersedia:'];

        // Tambahkan daftar pembimbing
        foreach ($pembimbingList as $pembimbing) {
            $instructions[] = ['   - ' . $pembimbing['nama_lengkap']];
        }

        // Isi instruksi
        $row = 1;
        foreach ($instructions as $instruction) {
            $instructionSheet->setCellValue('A' . $row, $instruction[0]);
            $row++;
        }

        // Style instruksi
        $instructionSheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '4472C4']]
        ]);
        $instructionSheet->getColumnDimension('A')->setWidth(60);

        // Set sheet aktif kembali ke template
        $spreadsheet->setActiveSheetIndex(0);

        // Download
        $filename = 'Template_Import_Soal_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function edit($id)
{
    $data['soal'] = $this->soalModel->find($id);
    $data['kategori'] = $this->kategoriModel->findAll();
    $data['pembimbing'] = $this->userModel->where('role', 'pembimbing')->findAll();

    return view('admin/soal/edit', $data);
}

public function preview($id)
{
    $soal = $this->soalModel
        ->select('soal.*, kategori_soal.nama_kategori as kategori, users.nama_lengkap as pembimbing')
        ->join('kategori_soal', 'kategori_soal.id = soal.kategori_id')
        ->join('users', 'users.id = soal.pembimbing_id')
        ->find($id);

    if (!$soal) {
        return redirect()->back()->with('error', 'Soal tidak ditemukan.');
    }

    return view('admin/soal/preview', ['soal' => $soal]);
}
public function duplicate($id)
{
    $original = $this->soalModel->find($id);
    if (!$original) {
        return redirect()->back()->with('error', 'Soal tidak ditemukan.');
    }

    // Hapus ID agar bisa insert baru
    unset($original['id']);
    $this->soalModel->insert($original);

    return redirect()->to('admin/soal')->with('success', 'Soal berhasil diduplikat.');
}

public function export()
{
    $kategoriId = $this->request->getVar('kategori_id');

    if (empty($kategoriId)) {
        return redirect()->back()->with('error', 'Pilih kategori untuk export.');
    }

    // Ambil data kategori
    $kategori = $this->kategoriModel->find($kategoriId);
    if (!$kategori) {
        return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
    }

    // Ambil soal berdasarkan kategori
    $soal = $this->soalModel
        ->select('soal.*, users.nama_lengkap as pembimbing')
        ->join('users', 'users.id = soal.pembimbing_id')
        ->where('soal.kategori_id', $kategoriId)
        ->findAll();

    if (empty($soal)) {
        return redirect()->back()->with('error', 'Tidak ada soal dalam kategori ini.');
    }

    // Buat spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'Pertanyaan');
    $sheet->setCellValue('B1', 'Pilihan A');
    $sheet->setCellValue('C1', 'Pilihan B');
    $sheet->setCellValue('D1', 'Pilihan C');
    $sheet->setCellValue('E1', 'Pilihan D');
    $sheet->setCellValue('F1', 'Pilihan E');
    $sheet->setCellValue('G1', 'Kunci Jawaban');
    $sheet->setCellValue('H1', 'Bobot');
    $sheet->setCellValue('I1', 'Kategori');
    $sheet->setCellValue('J1', 'Pembimbing');

    // Style header
    $sheet->getStyle('A1:J1')->getFont()->setBold(true);
    $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FFCCCCCC');

    // Data
    $row = 2;
    foreach ($soal as $s) {
        $sheet->setCellValue('A' . $row, strip_tags($s['pertanyaan']));
        $sheet->setCellValue('B' . $row, strip_tags($s['pilihan_a']));
        $sheet->setCellValue('C' . $row, strip_tags($s['pilihan_b']));
        $sheet->setCellValue('D' . $row, strip_tags($s['pilihan_c']));
        $sheet->setCellValue('E' . $row, strip_tags($s['pilihan_d']));
        $sheet->setCellValue('F' . $row, strip_tags($s['pilihan_e']));
        $sheet->setCellValue('G' . $row, $s['kunci_jawaban']);
        $sheet->setCellValue('H' . $row, $s['bobot']);
        $sheet->setCellValue('I' . $row, $kategori['nama_kategori']);
        $sheet->setCellValue('J' . $row, $s['pembimbing']);
        $row++;
    }

    // Auto size columns
    foreach (range('A', 'J') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Download
    $filename = 'Soal_' . str_replace(' ', '_', $kategori['nama_kategori']) . '_' . date('Y-m-d') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

}

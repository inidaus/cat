<?php

namespace App\Controllers\Pembimbing;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Peserta extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Ambil parameter per page dari request, default 10
        $perPage = (int)($this->request->getVar('per_page') ?? 10);

        // Validasi per page hanya boleh nilai tertentu
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $currentPage = $this->request->getVar('page') ?? 1;

        // Ambil data dengan pagination
        $builder = $this->userModel->where('role', 'peserta');
        $data['peserta'] = $builder->paginate($perPage, 'default', $currentPage);
        $data['pager'] = $this->userModel->pager;
        $data['currentPage'] = $currentPage;
        $data['perPage'] = $perPage;
        $data['total'] = $this->userModel->where('role', 'peserta')->countAllResults(false);
        $data['allowedPerPage'] = $allowedPerPage;

        return view('pembimbing/peserta/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Peserta'
        ];

        return view('pembimbing/peserta/create', $data);
    }

    public function save()
    {
        $this->userModel->save([
            'username' => $this->request->getPost('nim'),
            'password' => password_hash($this->request->getPost('nim'), PASSWORD_DEFAULT),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'angkatan' => $this->request->getPost('angkatan'),
            'role' => 'peserta',
            'aktif' => 1,
        ]);
        return redirect()->to('pembimbing/peserta');
    }

    public function edit($id)
    {
        $peserta = $this->userModel->find($id);
        
        if (!$peserta || $peserta['role'] !== 'peserta') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peserta tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Peserta',
            'peserta' => $peserta
        ];

        return view('pembimbing/peserta/edit', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $this->userModel->update($id, [
            'username' => $this->request->getPost('nim'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'angkatan' => $this->request->getPost('angkatan'),
        ]);
        return redirect()->to('pembimbing/peserta');
    }

    public function delete($id)
    {
        $peserta = $this->userModel->find($id);
        
        if (!$peserta || $peserta['role'] !== 'peserta') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peserta tidak ditemukan');
        }

        $this->userModel->delete($id);
        return redirect()->to('pembimbing/peserta')->with('success', 'Peserta berhasil dihapus.');
    }

    public function toggle($id)
    {
        $user = $this->userModel->find($id);

        if ($user && $user['role'] === 'peserta') {
            $newStatus = $user['aktif'] ? 0 : 1;
            $this->userModel->update($id, ['aktif' => $newStatus]);

            // Check if AJAX request
            if ($this->request->isAJAX()) {
                echo json_encode([
                    'success' => true,
                    'newStatus' => $newStatus
                ]);
                exit;
            } else {
                // Regular form submit - redirect back
                return redirect()->to('pembimbing/peserta')->with('success', 'Status peserta berhasil diubah');
            }
        } else {
            if ($this->request->isAJAX()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ]);
                exit;
            } else {
                return redirect()->to('pembimbing/peserta')->with('error', 'Peserta tidak ditemukan');
            }
        }
    }

    public function import()
    {
        $file = $this->request->getFile('file');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        if ($file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'File harus berformat CSV.');
        }

        $csvData = array_map('str_getcsv', file($file->getTempName()));
        $header = array_shift($csvData); // Remove header row

        $imported = 0;
        $errors = [];

        foreach ($csvData as $row) {
            if (count($row) < 3) continue; // Skip incomplete rows

            $data = [
                'username' => trim($row[0]),
                'nama_lengkap' => trim($row[1]),
                'angkatan' => trim($row[2]),
                'password' => password_hash(trim($row[0]), PASSWORD_DEFAULT),
                'role' => 'peserta',
                'aktif' => 1
            ];

            // Check if username already exists
            if ($this->userModel->where('username', $data['username'])->first()) {
                $errors[] = "NIM {$data['username']} sudah ada";
                continue;
            }

            if ($this->userModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Gagal import {$data['username']}";
            }
        }

        $message = "Berhasil import $imported peserta.";
        if (!empty($errors)) {
            $message .= " Error: " . implode(', ', array_slice($errors, 0, 3));
            if (count($errors) > 3) {
                $message .= " dan " . (count($errors) - 3) . " error lainnya.";
            }
        }

        return redirect()->to('pembimbing/peserta')->with('success', $message);
    }

    public function export()
    {
        $peserta = $this->userModel->where('role', 'peserta')->findAll();

        $filename = 'data_peserta_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, ['NIM', 'Nama Lengkap', 'Angkatan', 'Status']);

        // Data peserta
        foreach ($peserta as $p) {
            fputcsv($output, [
                $p['username'],
                $p['nama_lengkap'],
                $p['angkatan'],
                $p['aktif'] ? 'Aktif' : 'Tidak Aktif'
            ]);
        }

        fclose($output);
        exit;
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_peserta.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header template
        fputcsv($output, ['NIM', 'Nama Lengkap', 'Angkatan']);

        // Contoh data
        fputcsv($output, ['12345678', 'John Doe', '2023']);
        fputcsv($output, ['87654321', 'Jane Smith', '2023']);

        fclose($output);
        exit;
    }
}

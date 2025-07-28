<?php

namespace App\Controllers\Pembimbing;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
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
        $data['kategori'] = $this->kategoriModel->paginate($perPage, 'default', $currentPage);
        $data['pager'] = $this->kategoriModel->pager;
        $data['currentPage'] = $currentPage;
        $data['perPage'] = $perPage;
        $data['total'] = $this->kategoriModel->countAllResults(false);
        $data['allowedPerPage'] = $allowedPerPage;
        
        return view('pembimbing/kategori/index', $data);
    }

    public function store()
    {
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori')
        ];

        if ($this->kategoriModel->insert($data)) {
            $namaKategori = $data['nama_kategori'];
            return redirect()->to('pembimbing/kategori')->with('success', "Kategori '$namaKategori' berhasil ditambahkan!");
        } else {
            return redirect()->to('pembimbing/kategori')->with('error', 'Gagal menambahkan kategori.');
        }
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $namaKategori = $this->request->getPost('nama_kategori');
        
        $data = [
            'nama_kategori' => $namaKategori
        ];

        if ($this->kategoriModel->update($id, $data)) {
            return redirect()->to('pembimbing/kategori')->with('success', "Kategori '$namaKategori' berhasil diperbarui!");
        } else {
            return redirect()->to('pembimbing/kategori')->with('error', 'Gagal memperbarui kategori.');
        }
    }

    public function delete($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if (!$kategori) {
            return redirect()->to('pembimbing/kategori')->with('error', 'Kategori tidak ditemukan.');
        }

        $namaKategori = $kategori['nama_kategori'];
        
        if ($this->kategoriModel->delete($id)) {
            return redirect()->to('pembimbing/kategori')->with('success', "Kategori '$namaKategori' berhasil dihapus!");
        } else {
            return redirect()->to('pembimbing/kategori')->with('error', 'Gagal menghapus kategori.');
        }
    }
}

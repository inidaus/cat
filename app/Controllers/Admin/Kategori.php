<?php
namespace App\Controllers\Admin;

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

        return view('admin/kategori/index', $data);
    }

    public function save()
    {
        $namaKategori = $this->request->getPost('nama_kategori');

        $this->kategoriModel->save([
            'nama_kategori' => $namaKategori
        ]);

        return redirect()->back()->with('success', 'Kategori "' . $namaKategori . '" berhasil ditambahkan!');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $namaKategori = $this->request->getPost('nama_kategori');

        $this->kategoriModel->update($id, [
            'nama_kategori' => $namaKategori
        ]);

        return redirect()->back()->with('success', 'Kategori "' . $namaKategori . '" berhasil diperbarui!');
    }

    public function delete($id)
    {
        $kategori = $this->kategoriModel->find($id);
        if ($kategori) {
            $this->kategoriModel->delete($id);
            return redirect()->back()->with('success', 'Kategori "' . $kategori['nama_kategori'] . '" berhasil dihapus!');
        }
        return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
    }
}

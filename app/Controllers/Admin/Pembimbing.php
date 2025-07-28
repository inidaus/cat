<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Pembimbing extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['pembimbing'] = $this->userModel->where('role', 'pembimbing')->findAll();
        return view('admin/pembimbing/index', $data);
    }

    public function save()
    {
        $nip = $this->request->getPost('nip');
        $this->userModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $nip,
            'password'     => password_hash($nip, PASSWORD_DEFAULT),
            'role'         => 'pembimbing',
            'nip'          => $nip
        ]);
        return redirect()->back()->with('success', 'Pembimbing berhasil ditambahkan.');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $nip = $this->request->getPost('nip');

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $nip,
            'nip'          => $nip,
            'password'     => password_hash($nip, PASSWORD_DEFAULT), // Reset password ke NIP
        ];

        $this->userModel->update($id, $data);

        return redirect()->back()->with('success', 'Data pembimbing berhasil diperbarui dan password di-reset.');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->back()->with('success', 'Pembimbing berhasil dihapus.');
    }
}

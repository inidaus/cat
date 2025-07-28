<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Setting extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['admin'] = $this->userModel->where('role', 'admin')->findAll();
        return view('admin/setting/index', $data);
    }

    public function saveAdmin()
    {
        $this->userModel->save([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => 'admin'
        ]);
        return redirect()->back()->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function updateAdmin()
    {
        $id = $this->request->getPost('id');
        $password = $this->request->getPost('password');

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'username'     => $this->request->getPost('username')
        ];

        // Jika password diisi, update password juga
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $data);
        return redirect()->back()->with('success', 'Data admin berhasil diperbarui.');
    }

    public function deleteAdmin($id)
    {
        $this->userModel->delete($id);
        return redirect()->back()->with('success', 'Admin berhasil dihapus.');
    }


}

<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id'       => $user['id'],
                'nama'     => $user['nama_lengkap'],
                'role'     => $user['role'],
                'nama_lengkap' => $user['nama_lengkap'],
                'logged_in'=> true
            ]);

            if ($user['role'] === 'admin') {
                return redirect()->to('admin/dashboard');
            } elseif ($user['role'] === 'pembimbing') {
                return redirect()->to('pembimbing/dashboard');
            } elseif ($user['role'] === 'peserta') {
                return redirect()->to('peserta/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
}

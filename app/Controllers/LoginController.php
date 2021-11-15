<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        helper(['form']);
        return view('pages/login');
    }

    public function auth()
    {
        $session = session();
        $m_user = new UserModel();
        // create validator
        $validator = \Config\Services::validation();
        $validator->setRules([
            'email' => 'required',
            'password' => 'required'
        ]);
        $isDataValid = $validator->withRequest($this->request)->run();
        if ($isDataValid) {
            // get email and password
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            // search data
            $login_data = $m_user->where('email', $email)->first();
            if ($login_data) {
                $pass = $login_data['password'];
                $verify_pass = password_verify($password, $pass);
                // dd([$verify_pass, $pass, $password]);
                // dd($password, $pass);
                if ($verify_pass) {
                    $session_data = [
                        'id_user' => $login_data['id_user'],
                        'nama' => $login_data['nama'],
                        'username' => $login_data['username'],
                        'email' => $login_data['email'],
                        'logged_in' => true
                    ];
                    $session->set($session_data);
                    return redirect()->to(base_url() . '/dashboard')->with('success', 'Selamat datang, '.$login_data['username']);
                } else {
                    $session->setFlashdata('message', 'Password Salah');
                    return redirect()->to(base_url() . '/login')->with('error', 'Password Salah!');
                }
            } else {
                $session->setFlashdata('message', 'Email tidak ditemukan!');
                return redirect()->to(base_url() . '/login')->with('error', 'Email tidak ditemukan!');
            }
        } else {
            $session->setFlashdata('message', 'Email atau Password tidak valid!');
            return redirect()->to(base_url() . '/login')->with('error', 'Email atau Password tidak valid!');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url().'/login');
    }
}

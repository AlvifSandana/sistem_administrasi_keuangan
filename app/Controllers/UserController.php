<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // return view
        return view('pages/settings/account/index', $data);
    }

    /**
     * Create a new User
     */
    public function create()
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'nama' => 'required',
                'username' => 'required',
                'email' => 'required|valid_email',
                'password' => 'required|min_length[8]',
                'user_level' => 'required',
            ]);
            // validation process
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_user = new UserModel();
                // create new user with given data
                $new_user = $m_user->insert([
                    'nama' => $this->request->getPost('nama'),
                    'username' => $this->request->getPost('username'),
                    'email' => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'user_level' => $this->request->getPost('user_level'),
                ]);
                if ($new_user) {
                    return json_encode([
                        'status' => 'success',
                        'message' => 'Berhasil menambahkan user baru!',
                        'data' => []
                    ]);
                } else {
                    return json_encode([
                        'status' => 'failed',
                        'message' => 'Gagal menambahkan user baru!',
                        'data' => []
                    ]);
                }
            } else {
                return json_encode([
                    'status' => 'failed',
                    'message' => 'Validasi Gagal! Silahkan isi form dengan benar dan lengkap!',
                    'data' => [
                        'errors' => $validator->getErrors(),
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => [
                    'trace' => $th->getTrace(),
                ]
            ]);
        }
    }

    /**
     * Update current User by id
     */
    public function update($id_user)
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'nama' => 'required',
                'username' => 'required',
                'email' => 'required|valid_email',
                'current_password' => 'required',
                'new_password' => 'required|min_length[8]',
                'user_level' => 'required',
            ]);
            // validation process
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instanca
                $m_user = new UserModel();
                // get current user by id_user
                $current_user = $m_user->where('id_user', $id_user)->first();
                if ($current_user) {
                    // validate current password
                    $verify_password = password_verify($this->request->getPost('current_password'), $current_user['password']);
                    if ($verify_password) {
                        // update user data
                        $update_user = $m_user->update($id_user, [
                            'nama' => $this->request->getPost('nama'),
                            'username' => $this->request->getPost('username'),
                            'email' => $this->request->getPost('email'),
                            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
                            'user_level' => $this->request->getPost('user_level'),
                        ]);
                        if ($update_user) {
                            return json_encode([
                                'status' => 'success',
                                'message' => 'User data berhasil diperbarui! Silahkan login kembali untuk memulai sesi baru.',
                                'data' => $update_user,
                            ]);
                        } else {
                            return json_encode([
                                'status' => 'failed',
                                'message' => 'Gagal memperbarui user data!',
                                'data' => [],
                            ]);
                        }
                    } else {
                        return json_encode([
                            'status' => 'failed',
                            'message' => 'Validasi Password Gagal! Silahkan memasukkan password dengan benar!',
                            'data' => []
                        ]);
                    }
                } else {
                    return json_encode([
                        'status' => 'failed',
                        'message' => 'User tidak ditemukan!',
                        'data' => []
                    ]);
                }
            } else {
                return json_encode([
                    'status' => 'failed',
                    'message' => 'Validasi Gagal! Silahkan isi form dengan benar dan lengkap!',
                    'data' => [
                        'errors' => $validator->getErrors(),
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            return json_encode([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => [
                    'trace' => $th->getTrace(),
                ]
            ]);
        }
    }

    /**
     * Delete user by id
     */
    public function delete($id_user)
    {
        try {
            
        } catch (\Throwable $th) {
            
        }
    }
}

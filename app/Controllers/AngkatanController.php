<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;

class AngkatanController extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * Create a new data tahun angkatan
     * 
     * @return JSON
     */
    public function createAngkatan()
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'nama_angkatan' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_angkatan = new AngkatanModel();
                // insert data
                $angkatan = $m_angkatan->insert([
                    'nama_angkatan' => $this->request->getPost('nama_angkatan')
                ]);
                // result check
                if ($angkatan) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil menambahkan data tahun angkatan baru.';
                    $result['data'] = $angkatan;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menambahkan data tahun angkatan.';
                    $result['data'] = [];
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Gagal validasi data.';
                $result['data'] = $validator->getErrors();
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }

    /**
     * Update data tahun angkatan by id
     * 
     * @param int $id
     * @return JSON
     */
    public function updateAngkatan($id)
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'nama_angkatan' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid && $id != null) {
                // create model instance
                $m_angkatan = new AngkatanModel();
                // insert data
                $angkatan = $m_angkatan->update($id, [
                    'nama_angkatan' => $this->request->getPost('nama_angkatan')
                ]);
                // result check
                if ($angkatan) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil memperbarui data tahun angkatan dengan ID ' . $id;
                    $result['data'] = $angkatan;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal memperbarui data tahun angkatan dengan ID ' . $id;
                    $result['data'] = [];
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Gagal validasi data.';
                $result['data'] = $validator->getErrors();
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }

    /**
     * Delete data tahun angkatan by id
     * 
     * @param int $id
     * @return JSON
     */
    public function deleteAngkatan($id)
    {
        try {
            if ($id) {
                // create model instance
                $m_angkatan = new AngkatanModel();
                // insert data
                $angkatan = $m_angkatan->delete($id);
                // result check
                if ($angkatan) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil menghapus data tahun angkatan dengan ID ' . $id;
                    $result['data'] = $angkatan;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menghapus data tahun angkatan dengan ID ' . $id;
                    $result['data'] = [];
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'ID invalid!';
                $result['data'] = [];
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }
}

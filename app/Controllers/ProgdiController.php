<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProgdiModel;

class ProgdiController extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * Create a new progdi
     * 
     * @return JSON
     */
    public function createProgdi()
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validator rules
            $validator->setRules([
                'nama_progdi' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_progdi = new ProgdiModel();
                // insert data
                $progdi = $m_progdi->insert([
                    'nama_progdi' => strtoupper($this->request->getPost('nama_progdi'))
                ]);
                // result check
                if ($progdi) {
                    $result['status'] = 'success';
                    $result['message'] = 'Data berhasil ditambahkan.';
                    $result['data'] = $progdi;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menambahkan data.';
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
     * Update data progdi by id
     * 
     * @param int $id
     * @return JSON
     */
    public function updateProgdi($id)
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validator rules
            $validator->setRules([
                'nama_progdi' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid && $id != null) {
                // create model instance
                $m_progdi = new ProgdiModel();
                // insert data
                $progdi = $m_progdi->update($id, [
                    'nama_progdi' => strtoupper($this->request->getPost('nama_progdi'))
                ]);
                // result check
                if ($progdi) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil memperbarui data program studi dengan ID '.$id;
                    $result['data'] = $progdi;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal memperbarui data program studi dengan ID '.$id;
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
     * Delete data progdi by id
     * 
     * @param int $id
     * @return JSON
     */
    public function deleteProgdi($id)
    {
        try {
            if ($id != null) {
                // create model instance
                $m_progdi = new ProgdiModel();
                // insert data
                $progdi = $m_progdi->delete($id);
                // result check
                if ($progdi) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil menghapus data program studi dengan ID '.$id;
                    $result['data'] = $progdi;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menghapus data program studi dengan ID '.$id;
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

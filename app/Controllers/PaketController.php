<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\SemesterModel;

class PaketController extends BaseController
{
    public function index()
    {
    }

    public function get_all_paket()
    {
        try {
            // create model instance
            $m_paket = new PaketModel();
            // get all data from database
            $result = [
                'status' => 'success',
                'message' => 'data available',
                'data' => [
                    'paket' => $m_paket->findAll()
                ]
            ];
            // return json
            return json_encode($result);
        } catch (\Throwable $th) {
            //throw $th;
            $result = [
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => []
            ];
            return json_encode($result);
        }
    }

    public function get_data_for_create_update_paket()
    {
        try {
            // create model instance
            $m_semester = new SemesterModel();
            // get data from model
            $result = [
                'status' => 'success',
                'message' => 'data available',
                'data' => [
                    'semester' => $m_semester->findAll()
                ]
            ];
            // return json
            return json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => []
            ];
            return json_encode($result);
        }
    }

    public function create_paket()
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validator rules
            $validator->setRules([
                'nama_paket' => 'required',
                'semester_id' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_paket = new PaketModel();
                // get data from request
                $data = [
                    'nama_paket' => $this->request->getPost('nama_paket'),
                    'keterangan_paket' => $this->request->getPost('keterangan_paket'),
                    'semester_id' => $this->request->getPost('semester_id')
                ];
                // insert data to database
                $paket = $m_paket->insert($data);
                if ($paket) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil menambahkan paket baru.';
                    $result['data'] = $paket;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menambahkan paket baru.';
                    $result['data'] = $paket;
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Validasi gagal. Silahkan isi form dengan lengkap!';
                $result['data'] = $validator->getErrors();
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = $th->getTrace();
            return json_encode($result);
        }
    }

    public function update_paket($id)
    {
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validator rules
            $validator->setRules([
                'nama_paket' => 'required',
                'semester_id' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_paket = new PaketModel();
                // get data from request
                $data = [
                    'nama_paket' => $this->request->getPost('nama_paket'),
                    'keterangan_paket' => $this->request->getPost('keterangan_paket'),
                    'semester_id' => $this->request->getPost('semester_id')
                ];
                // update data to dataabase
                if ($m_paket->update($id, $data)) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil memperbarui paket!';
                    $result['data'] = $data;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal memperbarui paket.';
                    $result['data'] = [];
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Validasi gagal. Silahkan isi form dengan lengkap!';
                $result['data'] = $validator->getErrors();
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = $th->getTrace();
            return json_encode($result);
        }
    }

    public function delete_paket($id)
    {
        // TODO - create method for delete a paket by id
        try {
            // create model instance
            $m_paket = new PaketModel();
            // delete paket by given id
            if ($m_paket->delete($id)) {
                $result['status'] = 'success';
                $result['message'] = 'Berhasil menghapus paket!.';
                $result['data'] = [];
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Gagal menghapus paket.';
                $result['data'] = [];
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = $th->getTrace();
            return json_encode($result);
        }
    }
}

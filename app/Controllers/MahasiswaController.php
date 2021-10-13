<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;
use App\Models\MahasiswaModel;
use App\Models\ProgdiModel;

class MahasiswaController extends BaseController
{
    public function index()
    {
    }

    public function import_data_mahasiswa()
    {
        // TODO - create method for import data mahasiswa
    }

    public function export_data_mahasiswa()
    {
        // TODO - create method for export data mahasiswa
    }

    public function get_data_for_create_update_mahasiswa()
    {
        try {
            // create model instance
            $m_progdi = new ProgdiModel();
            $m_angkatan = new AngkatanModel();
            // get data from model
            $result = [
                'status' => 'success',
                'message' => 'data available',
                'data' => [
                    'progdi' => $m_progdi->findAll(),
                    'angkatan' => $m_angkatan->findAll()
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

    public function get_mahasiswa_by_id($id_mahasiswa)
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            // get data
            $mahasiswa = $m_mahasiswa->find($id_mahasiswa);
            if ($mahasiswa > 0) {
                $result['status'] = 'success';
                $result['message'] = 'Data available';
                $result['data'] = $mahasiswa;
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Data not available';
                $result['data'] = $mahasiswa;
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = $th;
            return json_encode($result);
        }
    }

    public function create_mahasiswa()
    {
        try {
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            // get data from request
            $data = [
                'nim' => $this->request->getPost('nim'),
                'nama_mahasiswa' => $this->request->getPost('nama_mahasiswa'),
                'progdi_id' => $this->request->getPost('progdi_id'),
                'angkatan_id' => $this->request->getPost('angkatan_id')
            ];
            // create a new data to database
            $m_mahasiswa->insert($data);
            // return 

        } catch (\Throwable $th) {
        }
    }

    /**
     * Update data mahasiswa by id
     * 
     * @return JSON
     */
    public function update_mahasiswa($id)
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'nim' => 'required',
                'nama_mahasiswa' => 'required',
                'progdi_id' => 'required',
                'angkatan_id' => 'required',
            ]);
            // validation chack 
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_mahasiswa = new MahasiswaModel();
                // update data
                $update_mahasiswa = $m_mahasiswa->update($id, [
                    'nim' => $this->request->getPost('nim'),
                    'nama_mahasiswa' => $this->request->getPost('nama_mahasiswa'),
                    'progdi_id' => $this->request->getPost('progdi_id'),
                    'angkatan_id' => $this->request->getPost('angkatan_id'),
                ]);
                if ($update_mahasiswa) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil memperbarui data mahasiswa dengan NIM ' . $this->request->getPost('nim');
                    $result['data'] = $update_mahasiswa;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal memperbarui data mahasiswa dengan NIM ' . $this->request->getPost('nim');
                    $result['data'] = $update_mahasiswa;
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Validasi gagal. Mohon isi form dengan lengkap!';
                $result['data'] = $validator->getErrors();
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = $th;
            return json_encode($result);
        }
    }

    /**
     * Delete data mahasiswa by id
     * 
     * @return JSON
     */
    public function delete_mahasiswa($id)
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // craete model instance
            $m_mahasiswa = new MahasiswaModel();
            // delete data mahasiswa by nim
            $delete_mahasiswa = $m_mahasiswa->delete($id);
            if ($delete_mahasiswa) {
                $result['status'] = 'success';
                $result['message'] = 'Berhasil menghapus data mahasiswa dengan ID ' . $id;
                $result['data'] = $delete_mahasiswa;
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Gagal menghapus data mahasiswa dengan ID ' . $id;
                $result['data'] = $delete_mahasiswa;
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = $th;
            return json_encode($result);
        }
    }
}

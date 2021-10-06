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
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // return view
        return view('pages/master/mahasiswa/index', $data);
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
            $result= [
                'status' => 'error',
                'message'=> $th->getMessage(),
                'data' => []
            ];
            return json_encode($result);
        }
    }

    public function create_mahasiswa()
    {
        // TODO - create method for mahasiswa
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

    public function update_mahasiswa($id)
    {
        // TODO - create method for update mahasiswa by NIM
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
            // update data mahasiswa by nim
            $m_mahasiswa->update($id, $data);
            // return 
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function delete_mahasiswa($id)
    {
        // TODO - create method for delete mahasiswa by NIM
        try {
            // craete model instance
            $m_mahasiswa = new MahasiswaModel();
            // delete data mahasiswa by nim
            $m_mahasiswa->delete($id);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

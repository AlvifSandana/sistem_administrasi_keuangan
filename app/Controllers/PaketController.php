<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\SemesterModel;

class PaketController extends BaseController
{
    public function index()
    {
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active menu
        $data['uri_segment'] = $request->uri->getSegment(1);
        // return view
        return view('pages/master/paket/index', $data);
    }

    public function get_all_paket()
    {
        try {
            // create model instance
            $m_paket = new PaketModel();
            // get all data from database
            $result = [
                'status' => 'success',
                'message'=> 'data available',
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
        // TODO - 2021/10/04 - create method for create a new paket
        try {
            // create model instance
            $m_paket = new PaketModel();
            // get data from request
            $data = [
                'nama_paket' => $this->request->getPost('nama_paket'),
                'keterangan_paket' => $this->request->getPost('keterangan_paket'),
                'semester_id' => $this->request->getPost('semester_id')
            ];
            // insert data to database
            $m_paket->insert($data);
            // return result

        } catch (\Throwable $th) {
        }
    }

    public function update_paket($id)
    {
        // TODO - create method for update a paket by id
        try {
            // create model instance
            $m_paket = new PaketModel();
            // get data from request
            $data = [
                'nama_paket' => $this->request->getPost('nama_paket'),
                'keterangan_paket' => $this->request->getPost('keterangan_paket'),
                'semester_id' => $this->request->getPost('semester_id')
            ];
            // update data to dataabase
            $m_paket->update($id, $data);
            // return

        } catch (\Throwable $th) {
            //throw $th;
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
            }
        } catch (\Throwable $th) {
        }
    }
}

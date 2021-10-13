<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\ProgdiModel;

class MasterMahasiswaController extends BaseController
{
    public function index()
    {
        // create model instance
        $m_mahasiswa = new MahasiswaModel();
        $m_angkatan = new AngkatanModel();
        $m_progdi = new ProgdiModel();
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // get data from model
        $data['data_mahasiswa'] = $m_mahasiswa->findAll();
        $data['data_angkatan'] = $m_angkatan->findAll();
        $data['data_progdi'] = $m_progdi->findAll();
        // return view
        return view('pages/master/mahasiswa/index', $data);
    }

    /**
     * Tambah data mahasiswa
     * 
     * @return JSON
     */
    public function create_mahasiswa()
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
                'nim' => 'required',
                'nama_mahasiswa' => 'required',
                'progdi_id' => 'required',
                'angkatan_id' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_mahasiswa = new MahasiswaModel();
                // insert data
                $mahasiswa = $m_mahasiswa->insert([
                    'nim' => $this->request->getPost('nim'),
                    'nama_mahasiswa' => $this->request->getPost('nama_mahasiswa'),
                    'progdi_id' => $this->request->getPost('progdi_id'),
                    'angkatan_id' => $this->request->getPost('angkatan_id'),
                ]);
                if ($mahasiswa) {
                    $result['status'] = 'success';
                    $result['message'] = 'Berhasil menambahkan data mahasiswa.';
                    $result['data'] = $mahasiswa;
                    return json_encode($result);
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'Gagal menambahkan data mahasiswa.';
                    $result['data'] = $mahasiswa;
                    return json_encode($result);
                }
            } else {
                $result['status'] = 'Failed';
                $result['message'] = 'Validasi gagal. Mohon isi form dengan lengkap!';
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
}

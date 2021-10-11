<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;

class MasterMahasiswaController extends BaseController
{
    public function index()
    {
        // create model instance
        $m_mahasiswa = new MahasiswaModel();
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // get data from model
        $data['data_mahasiswa'] = $m_mahasiswa->findAll();
        // return view
        return view('pages/master/mahasiswa/index', $data);
    }
}

<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;
use App\Models\ProgdiModel;
use App\Models\SemesterModel;

class DataPendukungController extends BaseController
{
    public function index()
    {
        // create model instance
        $m_semester = new SemesterModel();
        $m_progdi = new ProgdiModel();
        $m_angkatan = new AngkatanModel();
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // get data from models
        $data['semester'] = $m_semester->findAll();
        $data['progdi'] = $m_progdi->findAll();
        $data['angkatan'] = $m_angkatan->findAll();
        // get data paket
        $db = \Config\Database::connect();
        $builder = $db->table('paket');
        $query = $builder
            ->select('*, semester.*')
            ->join('semester', 'semester_id = semester.id_semester')
            ->get();
        
        $data['paket'] = $query->getResultArray();
        // show view
        return view('pages/master/datapendukung/index', $data);
    }
}

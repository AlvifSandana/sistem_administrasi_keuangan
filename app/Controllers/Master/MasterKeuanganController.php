<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\SemesterModel;

class MasterKeuanganController extends BaseController
{
    public function index()
    {
        // create model instance
        $m_paket = new PaketModel();
        $m_semester = new SemesterModel();
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active menu
        $data['uri_segment'] = $request->uri->getSegment(1);
        $data['data_paket'] = $m_paket->findAll();
        $data['data_semester'] = $m_semester->findAll();
        // return view
        return view('pages/master/keuangan/index', $data);
    }
}

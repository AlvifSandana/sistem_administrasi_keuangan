<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $request = \Config\Services::request();
        $data['uri_segment'] = $request->uri->getSegment(1);
        return view('pages/dashboard', $data);
    }
}

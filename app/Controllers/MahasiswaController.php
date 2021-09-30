<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

    public function create_mahasiswa()
    {
        // TODO - create method for mahasiswa
    }

    public function update_mahasiswa($nim){
        // TODO - create method for update mahasiswa by NIM
    }

    public function delete_mahasiswa($nim){
        // TODO - create method for delete mahasiswa by NIM
    }
}

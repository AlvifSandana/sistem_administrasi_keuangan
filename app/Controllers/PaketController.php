<?php

namespace App\Controllers;

use App\Controllers\BaseController;

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

    public function create_paket()
    {
        // TODO - create method for create new paket
    }

    public function update_paket($id){
        // TODO - create method for update a paket by id
    }

    public function delete_paket($id){
        // TODO - create method for delete a paket by id
    }
}

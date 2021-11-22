<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class BackupRestoreController extends BaseController
{
    public function index()
    {
        // create request instance
        $request = \Config\Services::request();
        // get uri segment for dynamic sidebar active item
        $data['uri_segment'] = $request->uri->getSegment(1);
        // return view
        return view('pages/master/backuprestore/index', $data);
    }

    /**
     * Backup database
     * 
     * @return file
     */
    public function backup()
    {
        try {
                
        } catch (\Throwable $th) {
            
        }
    }
}

<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;
use Prophecy\Doubler\Generator\ReflectionInterface;

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
            $filename = date('d-m-Y-H-i-s').'-db_keuangan.sql';
            $command = 'mysqldump --user='.env('database.default.username').' --password='.env('database.default.password').' '.env('database.default.database').' > '.ROOTPATH.'public/'.$filename;
            system($command);
            return redirect()->to(base_url().'/backup-restore')->with('success', 'Backup database berhasil! <a class="float-right" href="'.$filename.'"><i class="fas fa-download"></i> Download</a>');
        } catch (\Throwable $th) {
            return redirect()->to(base_url().'/backup-restore')->with('error', $th->getMessage());
        }
    }
}

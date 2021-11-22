<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PembayaranModel;
use App\Models\TagihanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends BaseController
{
    public function index()
    {
        //
    }

    /**
     * Create laporan tagihan mahasiswa
     * by NIM
     */
    public function generate_laporan_tagihan($nim = '')
    {
        try {
            // create model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_tagihan = new TagihanModel();
            $m_itemtagihan = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // get mahasiswa by id
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                // get tagihan by mahasiswa_id
                $tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                if (count($tagihan) > 0) {
                  // create spreadsheet instance
                  $spreadsheet = new Spreadsheet();  
                  // iterate all tagihan
                  foreach ($tagihan as $t) {
                      $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A2', '')
                        ->setCellValue('A2', '')
                        ->setCellValue('A2', '')
                        ->setCellValue('A2', '')
                        ->setCellValue('A2', '')
                        ->setCellValue('A2', '');
                  }
                } else {
                    # code...
                }
            } else {
                
            }
        } catch (\Throwable $th) {
            
        }
    }

    /**
     * Create laporan tagihan seluruh mahasiswa
     */
    public function generate_laporan_tagihan_all_mhs()
    {
        # code...
    }
}

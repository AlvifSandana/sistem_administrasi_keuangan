<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\TagihanModel;

class TagihanController extends BaseController
{
    public function index()
    {
        return view('pages/tagihan/index');
    }

    public function search_tagihan($nim)
    {
        helper(['form', 'url']);
        try {
            // model
            $m_tagihan = new TagihanModel();
            $m_mahasiswa = new MahasiswaModel(); 
            $m_paket = new PaketModel();
            $m_itempaket = new ItemPaketModel(); 
            // search tagihan by nim
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                $tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->first();
                if ($tagihan) {
                    $result = [
                        "id_tagihan" => $tagihan['id_tagihan'],
                        "nim" => $mahasiswa['nim'],
                        "nama_mahasiswa" => $mahasiswa['nama_mahasiswa'],
                        "detail_paket" => $m_paket->where('id_paket', $tagihan['paket_id'])->first(),
                        "item_paket" => "",
                    ];
                } else {
                    $result = null;
                }
            }
            echo json_encode($result);
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }
}

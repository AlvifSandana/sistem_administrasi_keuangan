<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\TagihanModel;

class TagihanController extends BaseController
{
    public function index()
    {
        $request = \Config\Services::request();
        $data['uri_segment'] = $request->uri->getSegment(1);
        return view('pages/tagihan/index', $data);
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
            $m_pembayaran = new PembayaranModel();
            // search tagihan by nim
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                // find tagihan by mahasiswa_id
                $tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                if ($tagihan) {
                    $tmp_tagihan = $tagihan;
                    // get detail 
                    for ($i=0; $i < count($tagihan); $i++) { 
                        $paket = $m_paket->where('id_paket', $tagihan[$i]['paket_id'])->findAll();
                        $item_paket = $m_itempaket->where('paket_id', $tagihan[$i]['paket_id'])->findAll();
                        $tmp_item_paket = $item_paket;
                        for ($j=0; $j < count($item_paket); $j++) { 
                            $pembayaran = $m_pembayaran->where(['item_id' => $item_paket[$j]['id_item'], 'mahasiswa_id' => $mahasiswa['id_mahasiswa']])->findAll();
                            $tmp_item_paket[$j]['detail_pembayaran'] = $pembayaran;
                        }
                        $tmp_tagihan[$i]['detail_paket'] = $paket;
                        $tmp_tagihan[$i]['detail_item_paket'] = $tmp_item_paket;
                    }

                    $result = [
                        'status' => 'success',
                        'message' => 'Data available',
                        'data' => [
                            'detail_mahasiswa' => $mahasiswa,
                            'detail_tagihan' => $tmp_tagihan,
                        ],
                    ];
                    // $result = [
                    //     "status" => "success",
                    //     "message" => "data available",
                    //     "data" => [
                    //         "id_tagihan" => $tagihan['id_tagihan'],
                    //         "nim" => $mahasiswa['nim'],
                    //         "nama_mahasiswa" => $mahasiswa['nama_mahasiswa'],
                    //         "detail_paket" => $m_paket->where('id_paket', $tagihan['paket_id'])->first(),
                    //         "item_paket" => $m_itempaket->where('paket_id', $tagihan['paket_id'])->findAll(),
                    //         "item_paket_terbayar" => $m_pembayaran->where(['mahasiswa_id' => $mahasiswa['id_mahasiswa'], 'paket_id' => $tagihan['paket_id']])->findAll(),
                    //     ],
                    // ];
                } else {
                    $result = [
                        "status" => "failed",
                        "message" => "Data not available",
                        "data" => [],
                    ];
                }
            } else {
                $result = [
                    "status" => "failed",
                    "message" => "Data not available",
                    "data" => [],
                ];
            }
            return json_encode($result);
        } catch (\Throwable $th) {
            return json_encode($th);
        }
    }
}

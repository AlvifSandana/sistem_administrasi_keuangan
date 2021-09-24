<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\TagihanModel;

class PembayaranController extends BaseController
{
    public function index()
    {
        // request instance
        $request = \Config\Services::request();
        // data for view
        $data['uri_segment'] = $request->uri->getSegment(1);
        // return view
        return view('pages/pembayaran/index', $data);
    }

    public function search_pembayaran($nim)
    {  
        try {
            // session instance
            $session = session();
            // model
            $m_tagihan = new TagihanModel();
            $m_mahasiswa = new MahasiswaModel(); 
            $m_paket = new PaketModel();
            $m_itempaket = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // search tagihan & pembayaran by nim
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                $pembayaran = $m_pembayaran->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                if ($pembayaran) {
                    $session->setFlashdata('success', 'Data ditemukan!');
                    $result = [
                        "status"  => "success",
                        "message" => "available",
                        "data" => [ $mahasiswa, $pembayaran ],
                    ];
                } else {
                    $session->setFlashdata('error', 'Pembayaran kosong!');
                    $result = [
                        "status"  => "success",
                        "message" => "data not available",
                        "data" => [
                            "mahasiswa" => $mahasiswa,
                            "pembayaran"=> []
                        ],
                    ];
                }
            } else {
                $session->setFlashdata('error', 'NIM tidak ditemukan!');
                $result = [
                    "status"  => "failed",
                    "message" => "not available",
                    "data" => [],
                ];
            }
            return json_encode($result);
        } catch (\Throwable $th) {
            $session->setFlashdata('error', 'Error Occured!');
            $result = [
                "status"  => "error",
                "message" => $th
            ];
            return json_encode($result);
        }
    }
}

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
                        "data" => [
                            "mahasiswa" => $mahasiswa,
                            "pembayaran" => $pembayaran,
                        ],
                    ];
                } else {
                    $session->setFlashdata('error', 'Pembayaran kosong!');
                    $result = [
                        "status"  => "success",
                        "message" => "data not available",
                        "data" => [
                            "mahasiswa" => $mahasiswa,
                            "pembayaran" => []
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

    public function get_detail_item_tagihan_by_paket_id($id)
    {
        try {
            // create model instance
            $m_itempaket = new ItemPaketModel();
            // get data from model
            $item_paket = $m_itempaket->where("paket_id", $id)->findAll();
            if (count($item_paket) > 0) {
                $result = [
                    "status" => "success",
                    "message" => "data available",
                    "data" => $item_paket,
                ];
            } else {
                $result = [
                    "status" => "failed",
                    "message" => "data not available",
                    "data" => null,
                ];
            }
            return json_encode($result);
        } catch (\Throwable $th) {
            $result = [
                "status" => "failed",
                "message" => $th,
                "data" => null,
            ];
            return json_encode($result);
        }
    }

    public function add_pembayaran()
    {
        try {
            // get data from request
            // create vaidator
            $validator = \Config\Services::validation();
            $validator->setRules([
                'paket_id' => 'required',
                'item_id' => 'required',
                'mahasiswa_id' => 'required',
                'tanggal_pembayaran' => 'required',
                'nominal_pembayaran' => 'required',
                'user_id' => 'required'
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model
                $m_pembayaran = new PembayaranModel();
                // insert data 
                $m_pembayaran->insert([
                    'paket_id' => $this->request->getPost('paket_id'),
                    'item_id' => $this->request->getPost('item_id'),
                    'mahasiswa_id' => $this->request->getPost('mahasiswa_id'),
                    'tanggal_pembayaran' => $this->request->getPost('tanggal_pembayaran'),
                    'nominal_pembayaran' => $this->request->getPost('nominal_pembayaran'),
                    'keterangan_pembayaran' => $this->request->getPost('keterangan_pembayaran'),
                    'user_id' => $this->request->getPost('user_id')
                ]);
                $result = [
                    "status" => "success",
                    "message" => "Berhasil menambahkan pembayaran.",
                    "data" => []
                ];
                // return JSON
                return json_encode($result);
            } else {
                $result = [
                    "status" => "failed",
                    "message" => "Validasi gagal. Mohon isi form dengan lengkap.",
                    "data" => []
                ];
                // return JSON
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result = [
                "status" => "error",
                "message" => $th->getMessage(),
                "data" => []
            ];
            return json_encode($result);
        }
    }
}

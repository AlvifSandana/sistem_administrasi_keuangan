<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AngkatanModel;
use App\Models\ItemPaketModel;
use App\Models\MahasiswaModel;
use App\Models\PaketModel;
use App\Models\PembayaranModel;
use App\Models\ProgdiModel;
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
            // $m_tagihan = new TagihanModel();            
            // $m_paket = new PaketModel();
            // $m_itempaket = new ItemPaketModel();
            $m_mahasiswa = new MahasiswaModel();
            $m_pembayaran = new PembayaranModel();
            $m_progdi = new ProgdiModel();
            $m_angkatan = new AngkatanModel();
            // search tagihan & pembayaran by nim
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                $progdi = $m_progdi->where('id_progdi', $mahasiswa['progdi_id'])->first();
                $angkatan = $m_angkatan->where('id_angkatan', $mahasiswa['angkatan_id'])->first();
                $pembayaran = $m_pembayaran->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->findAll();
                $mahasiswa['progdi'] = $progdi['nama_progdi'];
                $mahasiswa['angkatan'] = $angkatan['nama_angkatan'];
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
                $m_itempaket = new ItemPaketModel();
                $m_tagihan = new TagihanModel();
                /**
                 * validating pembayaran:
                 * 
                 * nominal pembayaran > nominal item tagihan ? pembayaran failed : pembayaran success 
                 * nominal pembayaran >= sisa tagihan ? pembayaran failed : pembayaran success
                 */
                $item_tagihan = $m_itempaket->find($this->request->getPost('item_id'));
                $pembayaran = $m_pembayaran->where('item_id', $this->request->getPost('item_id'))->findAll();
                $all_item_tagihan = $m_itempaket->where('paket_id', $this->request->getPost('paket_id'))->findAll();
                $all_item_pembayaran = $m_pembayaran->where('paket_id', $this->request->getPost('paket_id'))->where('mahasiswa_id', $this->request->getPost('mahasiswa_id'))->findAll();
                if ($item_tagihan != null) {
                    if ($this->request->getPost('nominal_pembayaran') > $item_tagihan['nominal_item']) {
                        $result = [
                            "status" => "failed",
                            "message" => "Validasi gagal. Mohon isi nominal pembayaran yang sesuai!",
                            "data" => []
                        ];
                    } else {
                        $total_terbayar = 0;
                        if ($pembayaran != null) {
                            // get total pembayaran
                            foreach ($pembayaran as $p) {
                                $total_terbayar += $p['nominal_pembayaran'];
                            }
                        }
                        // dd($pembayaran);
                        $total_terbayar += $this->request->getPost('nominal_pembayaran');
                        if ($total_terbayar > $item_tagihan['nominal_item']) {
                            $result = [
                                "status" => "failed",
                                "message" => "Validasi gagal. Total pembayaran melebihi sisa tagihan. Mohon isi nominal pembayaran yang sesuai!",
                                "data" => []
                            ];
                        } else {
                            // change status tagihan when sisa tagihan = 0
                            $total_tagihan = 0;
                            $total_pembayaran = 0;
                            foreach ($all_item_tagihan as $ait) {
                                $total_tagihan += $ait['nominal_item'];
                            }
                            foreach ($all_item_pembayaran as $aip) {
                                $total_pembayaran += $aip['nominal_pembayaran'];
                            }
                            $sisa_tagihan = $total_tagihan - ($this->request->getPost('nominal_pembayaran') + $total_pembayaran);
                            // dd($sisa_tagihan, $total_tagihan, $total_terbayar, $total_pembayaran);
                            if ($sisa_tagihan == 0) {
                                $paket_id = $this->request->getPost('paket_id');
                                $mahasiswa_id = $this->request->getPost('mahasiswa_id');
                                $find_tagihan = $m_tagihan->where('paket_id', $paket_id)->where('mahasiswa_id', $mahasiswa_id)->first();
                                $m_tagihan->update($find_tagihan['id_tagihan'], [
                                    'status_tagihan' => 'Lunas'
                                ]);
                            }
                            // insert data 
                            $result = [
                                "status" => "success",
                                "message" => "Berhasil menambahkan pembayaran.",
                                "data" => $m_pembayaran->insert([
                                    'paket_id' => $this->request->getPost('paket_id'),
                                    'item_id' => $this->request->getPost('item_id'),
                                    'mahasiswa_id' => $this->request->getPost('mahasiswa_id'),
                                    'tanggal_pembayaran' => $this->request->getPost('tanggal_pembayaran'),
                                    'nominal_pembayaran' => $this->request->getPost('nominal_pembayaran'),
                                    'keterangan_pembayaran' => $this->request->getPost('keterangan_pembayaran'),
                                    'user_id' => $this->request->getPost('user_id')
                                ])
                            ];
                        }
                    }
                } else {
                    $result = [
                        "status" => "error",
                        "message" => "Item tidak ditemukan!",
                        "data" => []
                    ];
                }
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

    public function get_detail_pembayaran_by_nim($nim)
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null
        ];

        try {
            $db = \Config\Database::connect();
            // model instance
            $m_mahasiswa = new MahasiswaModel();
            $m_paket = new PaketModel();
            $m_tagihan = new TagihanModel();
            $m_itempaket = new ItemPaketModel();
            $m_pembayaran = new PembayaranModel();
            // array of sql query
            $sql = 'SELECT * FROM pembayaran where paket_id = ? AND mahasiswa_id = ?';
            $hasil = [];
            // get data mahasiswa
            $mahasiswa = $m_mahasiswa->where('nim', $nim)->first();
            if ($mahasiswa) {
                // get tagihan by mahasiswa_id
                $tagihan = $m_tagihan->where('mahasiswa_id', $mahasiswa['id_mahasiswa'])->first();
                if ($tagihan) {
                    $paket = $m_paket->where('id_paket', $tagihan['paket_id'])->first();
                    // get item paket
                    $item_paket = $m_itempaket->where('paket_id', $paket['id_paket'])->findAll();
                    // get pembayaran by item
                    foreach ($item_paket as $i_p) {
                        $hasil[$i_p['nama_item']] = $m_pembayaran->where(['paket_id' => $i_p['paket_id'], 'mahasiswa_id' => $mahasiswa['id_mahasiswa'], 'item_id' => $i_p['id_item']])->findAll();
                        //array_push($hasil, $m_pembayaran->where(['paket_id' => $i_p['paket_id'], 'mahasiswa_id' => $mahasiswa['id_mahasiswa'], 'item_id' => $i_p['id_item']])->findAll());
                    }
                    // result
                    $result['status'] = 'success';
                    $result['message'] = 'data avalable';
                    $result['data'] = $hasil;
                } else {
                    $result['status'] = 'failed';
                    $result['message'] = 'paket not available';
                }
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'mahasiswa not avalable';
            }
            // return 
            return json_encode($result);
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            return json_encode($result);
        }
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;

class ItemPaketController extends BaseController
{
    public function index()
    {
    }

    /**
     * Get all item by id_paket
     * 
     * @return JSON
     */
    public function get_all_item_by_id_paket($id_paket)
    {
        try {
            // create model instance
            $m_itempaket = new ItemPaketModel();
            // get item paket by id_paket
            $item_paket = $m_itempaket->where('paket_id', $id_paket)->findAll();
            if (count($item_paket) > 0) {
                $result = [
                    'status' => 'success',
                    'message' => 'data available',
                    'data' => $item_paket
                ];
            } else {
                $result = [
                    'status' => 'failed',
                    'message' => 'data not available',
                    'data' => []
                ];
            }
            // return JSON
            return json_encode($result);
        } catch (\Throwable $th) {
            //throw $th;
            $result = [
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Tambah item paket baru
     * 
     * @return JSON
     */
    public function add_item_paket()
    {
        // result 
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // validator
            $validator = \Config\Services::validation();
            // set validator rules
            $validator->setRules([
                'paket_id' => 'required',
                'nama_item' => 'required',
                'nominal_item' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_itempaket = new ItemPaketModel();
                // insert data
                $result['status'] = 'success';
                $result['message'] = 'Berhasil menambahkan item tagihan.';
                $result['data'] = $m_itempaket->insert([
                    'paket_id' => $this->request->getPost('paket_id'),
                    'nama_item' => $this->request->getPost('nama_item'),
                    'nominal_item' => $this->request->getPost('nominal_item'),
                    'keterangan_item' => $this->request->getPost('keterangan_item'),
                ]);
                // return JSON
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Validasi gagal. Mohon isi form dengan lengkap!';
                $result['data'] = [];
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = '';
            return json_encode($result);
        }
    }

    /**
     * Get item paket by id_item
     * 
     * @return JSON
     */
    public function get_item_paket_by_id($id_item)
    {
        // result
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // create model instance
            $m_itempaket = new ItemPaketModel();
            // get item paket
            $item_paket = $m_itempaket->where('id_item', $id_item)->first();
            if (count($item_paket) > 0) {
                $result['status'] = 'success';
                $result['message'] = 'data available';
                $result['data'] = $item_paket;
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'data not available';
                $result['data'] = $item_paket;
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }

    /**
     * Update item paket berdasarkan id_item
     * 
     * @return JSON
     */
    public function update_item_paket($id_item)
    {
        // result
        $result = [
            'status' => '',
            'message' => '', 
            'data' => null,
        ];
        try {
            // create validator
            $validator = \Config\Services::validation();
            // set validation rules
            $validator->setRules([
                'paket_id' => 'required',
                'nama_item' => 'required',
                'nominal_item' => 'required',
            ]);
            // validation check
            $isDataValid = $validator->withRequest($this->request)->run();
            if ($isDataValid) {
                // create model instance
                $m_itempaket = new ItemPaketModel();
                // update data item tagihan
                $result['status'] = 'success';
                $result['message'] = 'Berhasil memperbarui data item tagihan.';
                $result['data'] = $m_itempaket->update($id_item, [
                    'nama_item' => $this->request->getPost('nama_item'),
                    'nominal_item' => $this->request->getPost('nominal_item'),
                    'keterangan_item' => $this->request->getPost('keterangan_item'),
                ]);
                // return JSON
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Validasi gagal. Mohon isi form dengan lengkap!';
                $result['data'] = [];
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }

    /**
     * Hapus item paket berdasarkan id_item
     * 
     * @return JSON
     */
    public function delete_item_paket($id_item)
    {
        $result = [
            'status' => '',
            'message' => '',
            'data' => null,
        ];
        try {
            // create model instance
            $m_itempaket = new ItemPaketModel();
            // delete data by id_item
            $res = $m_itempaket->delete($id_item);
            if ($res) {
                $result['status'] = 'success';
                $result['message'] = 'Item tagihan dengan id_item ='.$id_item.' berhasil dihapus.';
                $result['data'] = $res;
                return json_encode($result);
            } else {
                $result['status'] = 'failed';
                $result['message'] = 'Item tagihan dengan id_item ='.$id_item.' gagal dihapus.';
                $result['data'] = $res;
                return json_encode($result);
            }
        } catch (\Throwable $th) {
            //throw $th;
            $result['status'] = 'error';
            $result['message'] = $th->getMessage();
            $result['data'] = [];
            return json_encode($result);
        }
    }
}

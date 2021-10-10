<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemPaketModel;

class ItemPaketController extends BaseController
{
    public function index()
    {
    }

    // get all item by id_paket
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

    public function update_item_paket($id)
    {
    }

    public function delete_item_paket($id)
    {
    }
}

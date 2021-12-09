<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        // create request instance
        $request = \Config\Services::request();
        // get uri segment
        $data['uri_segment'] = $request->uri->getSegment(1);
        // get nominal tagihan & pembayaran keseluruhan mahasiswa
        $db = \Config\Database::connect();
        $builder_tagihan = $db->table('item_paket');
        $builder_pembayaran= $db->table('pembayaran');
        $query1 = $builder_tagihan
            ->select('SUM(item_paket.nominal_item) as total_tagihan')
            ->join('tagihan', 'item_paket.paket_id = tagihan.paket_id')
            ->get();
        $query2 = $builder_pembayaran
            ->select('SUM(nominal_pembayaran) as total_pembayaran')
            ->get();
        $data['total_tagihan'] = $query1->getResultArray();
        $data['total_pembayaran'] = $query2->getResultArray();
        // return view with data
        return view('pages/dashboard', $data);
    }
}

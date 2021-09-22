<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TagihanController extends BaseController
{
    public function index()
    {
        return view('pages/tagihan/index');
    }
}

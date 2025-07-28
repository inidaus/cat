<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TestTimezone extends BaseController
{
    public function index()
    {
        // Force timezone untuk testing
        date_default_timezone_set('Asia/Jakarta');
        
        return view('test_timezone');
    }
}

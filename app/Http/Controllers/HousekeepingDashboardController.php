<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HousekeepingDashboardController extends Controller
{
     public function index()
    {
        return view('housekeeping.index'); // pastikan view ini ada di resources/views/resepsionis/dashboard.blade.php
    }
}

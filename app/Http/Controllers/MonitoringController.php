<?php

namespace App\Http\Controllers;

use App\pegawai;
use App\instansi;
use App\masterbulanan;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    //
    public function monitoringinstansiminggu(Request $request)
    {
        // $masterbulanan=
        return view('monitoring.rekapmingguinstansi');
    }
}

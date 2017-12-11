<?php

namespace App\Http\Controllers;
use App\version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('throttle:50000,1');
    }
    public function index(){
        $tables=version::where('id','=','1')->first();
        return $tables;
    }
}

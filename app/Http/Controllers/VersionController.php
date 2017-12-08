<?php

namespace App\Http\Controllers;
use App\version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    //
    public function index(){
        $tables=version::where('id','=','1')->first();
        return $tables;
    }
}

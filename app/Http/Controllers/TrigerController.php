<?php

namespace App\Http\Controllers;
use App\triger;
use Illuminate\Http\Request;

class TrigerController extends Controller
{
    //
    public function index(){
        $table=triger::all();
        return $table;
    }
}

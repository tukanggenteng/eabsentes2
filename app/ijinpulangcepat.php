<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ijinpulangcepat extends Model
{
    //
    protected $table="ijinterlambats";

    public function rekapbulanan(){
        return $this->belongsTo(rekapbulanan::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

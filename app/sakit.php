<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sakit extends Model
{
    //
    protected $table="sakits";

    public function rekapbulanan(){
        return $this->belongsTo(rekapbulanan::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

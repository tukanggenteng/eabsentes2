<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tugasbelajar extends Model
{
    //
    protected $table="tugasbelajars";

    public function rekapbulanan(){
        return $this->belongsTo(rekapbulanan::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

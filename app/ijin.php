<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ijin extends Model
{
    //
    protected $table="ijins";

    public function rekapbulanan(){
        return $this->belongsTo(rekapbulanan::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

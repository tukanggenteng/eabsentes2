<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rapatundangan extends Model
{
    //
    protected $table="rapatundangans";

    public function rekapbulanan(){
        return $this->belongsTo(rekapbulanan::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cuti extends Model
{
    //
    protected $table="cutis";

    public function rekapbulanan(){
        return $this->belongsTo(rekapbulanan::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

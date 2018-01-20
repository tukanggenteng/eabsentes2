<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class historycrashraspberry extends Model
{
    //
    protected $table="historycrashraspberrys";

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

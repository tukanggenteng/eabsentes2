<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class historyfingerpegawai extends Model
{
    //
    protected $table="historyfingerpegawais";

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

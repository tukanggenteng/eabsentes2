<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{
    //
    protected $table="pegawais";

    public function att(){
        return $this->hasMany(att::class);
    }

    public function atts_tran(){
        return $this->hasMany(atts_tran::class);
    }

    public function rulejadwalpegawai(){
        return $this->hasMany(rulejadwalpegawai::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function rekapbulanan(){
        return $this->hasMany(rekapbulanan::class);
    }

    public function fingerpegawai(){
        return $this->hasMany(fingerpegawai::class);
    }
}

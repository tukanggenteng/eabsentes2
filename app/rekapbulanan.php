<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rekapbulanan extends Model
{
    //
    protected $table="rekapbulanans";

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function tugasbelajar(){
        return $this->hasMany(tugasbelajar::class);
    }

    public function sakit(){
        return $this->hasMany(sakit::class);
    }

    public function cuti(){
        return $this->hasMany(cuti::class);
    }

    public function ijin(){
        return $this->hasMany(ijin::class);
    }
}

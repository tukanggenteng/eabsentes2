<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class instansi extends Model
{
    //
    protected $table="instansis";

    public function att(){
        return $this->hasMany(att::class);
    }

    public function pegawai(){
        return $this->hasMany(pegawai::class);
    }

    public function rekapbulanan(){
        return $this->hasMany(rekapbulanan::class);
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
    public function rulejadwalpegawai(){
        return $this->hasMany(rulejadwalpegawai::class);
    }

    public function raspberrystatus(){
        return $this->hasMany(raspberrystatu::class);
    }
}

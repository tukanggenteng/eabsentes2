<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class keterangan_absen extends Model
{
    //
    protected $table="keterangan_absens";

    public function jadwalkerja(){
        return $this->belongsTo(jadwalkerja::class);
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function jenisabsen(){
        return $this->hasMany(jenisabsen::class);
    }
}

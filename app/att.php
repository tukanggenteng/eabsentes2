<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class att extends Model
{
    //
    protected $table="atts";

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function transaksiabsen(){
        return $this->hasMany(transaksiabsen::class);
    }
}

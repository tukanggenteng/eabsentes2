<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai_Hari_Libur extends Model
{
    //
    protected $table="pegawai_hari_liburs";


    public function pegawai(){
        return $this->belongsTo(Pegawai::class);
    }
}

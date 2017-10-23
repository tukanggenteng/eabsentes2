<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fingerpegawai extends Model
{
    //
    protected $table="fingerpegawais";

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }
}

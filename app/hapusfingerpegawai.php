<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class hapusfingerpegawai extends Model
{
    //
    protected $table="hapusfingerpegawais";

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }
}

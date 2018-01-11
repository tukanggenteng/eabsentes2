<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class adminpegawai extends Model
{
    //
    protected $table="adminpegawais";

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }
}

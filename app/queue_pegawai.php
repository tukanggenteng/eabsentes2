<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class queue_pegawai extends Model
{
    //
    protected $table="queue_pegawais";


    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function macaddress(){
        return $this->belongsTo(macaddresse::class);
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

}

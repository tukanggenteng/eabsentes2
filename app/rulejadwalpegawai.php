<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rulejadwalpegawai extends Model
{
    //
    use SoftDeletes;
//    protected $table="rulejadwalpegawais";
    protected $date=['deleted_at'];

    public function jadwalkerja(){
        return $this->belongsTo(jadwalkerja::class);
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

}

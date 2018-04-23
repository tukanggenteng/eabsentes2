<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class dokter extends Model
{
    //
    use SoftDeletes;
   protected $table="dokters";
    // protected $table=['deleted_at'];
    protected $hidden = ["deleted_at"];

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

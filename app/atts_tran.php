<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class atts_tran extends Model
{
    //
    protected $table="atts_trans";


    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

}

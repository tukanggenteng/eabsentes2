<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class log_att_tran extends Model
{
    //

    protected $table="log_att_trans";


    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function macaddress(){
        return $this->belongsTo(macaddresse::class);
    }

}

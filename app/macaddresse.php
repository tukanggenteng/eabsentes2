<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class macaddresse extends Model
{
    //
    protected $table="macaddresses";
    
    public function instansi(){
        return $this->belongsTo(instansi::class);
    }

    public function log_att_tran(){
        return $this->belongsTo(log_att_tran::class);
    }
}

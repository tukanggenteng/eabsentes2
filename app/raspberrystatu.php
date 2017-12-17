<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class raspberrystatu extends Model
{
    //
    protected $table="raspberrystatus";

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

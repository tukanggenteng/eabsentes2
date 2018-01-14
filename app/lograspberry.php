<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class lograspberry extends Model
{
    //
    protected $table="lograspberrys";

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

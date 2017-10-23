<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class harikerja extends Model
{
    //
    protected $table="harikerjas";

    public function jadwalkerja(){
        return $this->belongsTo(jadwalkerja::class);
    }
}

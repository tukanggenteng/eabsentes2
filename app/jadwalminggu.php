<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jadwalminggu extends Model
{
    //
    protected $table="jadwalminggus";

    public function jadwalkerja(){
        return $this->belongsTo(jadwalkerja::class);
    }
    
    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

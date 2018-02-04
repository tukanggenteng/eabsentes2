<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class jadwalkerja extends Model
{
    //
    use SoftDeletes;
//    protected $table="jadwalkerjas";

    protected $date=['deleted_at'];

    public function harikerja(){
        return $this->hasMany(harikerja::class);
    }

    public function rulejadwalpegawai(){
        return $this->hasMany(rulejadwalpegawai::class);
    }

    public function rulejammasuk(){
        return $this->hasMany(rulejammasuk::class);
    }

    public function istirahat(){
        return $this->hasOne(istirahat::class);
    }
}

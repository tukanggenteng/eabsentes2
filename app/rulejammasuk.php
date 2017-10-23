<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rulejammasuk extends Model
{
    //
    use SoftDeletes;
//    protected $table="rulejammasuks";
    protected $date=['deleted_at'];

    public function jadwalkerja(){
        return $this->belongsTo(jadwalkerja::class);
    }
}

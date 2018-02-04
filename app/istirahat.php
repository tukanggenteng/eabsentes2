<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class istirahat extends Model
{
    //
    
    protected $date=['deleted_at'];

    use SoftDeletes;

    public function jadwalkerja(){
        return $this->belongsTo(jadwalkerja::class);
    }
}

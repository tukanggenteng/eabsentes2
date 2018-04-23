<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ruangan extends Model
{
    //
    use SoftDeletes;
   protected $table="ruangans";
    // protected $table=['deleted_at'];
    protected $hidden = ["deleted_at"];

    public function instansi(){
        return $this->belongsTo(instansi::class);
    }
}

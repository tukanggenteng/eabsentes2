<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ruanganuser extends Model
{
    //
    use SoftDeletes;
   protected $table="ruanganusers";
    // protected $table=['deleted_at'];
    protected $hidden = ["deleted_at"];

    public function ruangan(){
        return $this->belongsTo(ruangan::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

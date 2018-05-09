<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rulenotification extends Model
{
    //
    protected $table="rulenotifications";

    public function user(){
        return $this->hasMany(User::class);
    }
}

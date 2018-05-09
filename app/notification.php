<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    //
    protected $table="notifications";

    public function rulenotification(){
        return $this->hasMany(rulenotification::class);
    }
}

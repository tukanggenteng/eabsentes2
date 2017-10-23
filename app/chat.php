<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    //
    protected $table="chats";

    public function user(){
        return $this->belongsTo(user::class);
    }
}

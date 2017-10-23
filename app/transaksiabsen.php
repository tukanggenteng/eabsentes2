<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class transaksiabsen extends Model
{
    //
    protected $table="transaksiabsens";

    public function att(){
        return $this->belongsTo(att::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class perawatruangan extends Model
{
    //
    use SoftDeletes;
   protected $table="perawatruangans";
    // protected $table=['deleted_at'];
    protected $hidden = ["deleted_at"];

    public function pegawai(){
        return $this->belongsTo(pegawai::class);
    }

    public function ruangan(){
        return $this->belongsTo(ruangan::class);
    }
}

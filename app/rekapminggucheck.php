<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rekapminggucheck extends Model
{
    //
    use SoftDeletes;
    protected $table="rekapmingguchecks";
        // protected $table=['deleted_at'];
        protected $hidden = ["deleted_at"];
}

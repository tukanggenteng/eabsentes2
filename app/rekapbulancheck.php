<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rekapbulancheck extends Model
{
    //
    use SoftDeletes;
    protected $table="rekapbulanchecks";
        // protected $table=['deleted_at'];
        protected $hidden = ["deleted_at"];
}

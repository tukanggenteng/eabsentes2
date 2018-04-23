<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class attendancecheck extends Model
{
    //
    use SoftDeletes;
    protected $table="attendancechecks";
        // protected $table=['deleted_at'];
        protected $hidden = ["deleted_at"];
}

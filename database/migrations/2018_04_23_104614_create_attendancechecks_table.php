<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancechecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('attendancechecks',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('tanggalcheckattendance');
            $table->boolean('statuscheckattendance');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rekapbulanchecks',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('tanggalcheckrekapbulan');
            $table->boolean('statuscheckrekapbulan');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rekapmingguchecks',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('tanggalcheckrekapminggu');
            $table->boolean('statuscheckrekapminggu');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('attendancechecks');
        Schema::dropIfExists('rekapbulanchecks');
        Schema::dropIfExists('rekapmingguchecks');
    }
}

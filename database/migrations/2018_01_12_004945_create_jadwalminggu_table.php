<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJadwalmingguTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('jadwalminggus',function(Blueprint $table){
            $table->increments('id');
            $table->integer('minggu');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::table('jadwalminggus',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('jadwalminggus');
    }
}

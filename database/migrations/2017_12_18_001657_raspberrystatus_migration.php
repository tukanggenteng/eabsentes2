<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RaspberrystatusMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('raspberrystatus',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('instansi_id');
            $table->string('status');
            $table->time('jamstatus_raspberry')->null();
            $table->date('tanggal_raspberry')->null();
            $table->timestamps();
        });

        Schema::table('raspberrystatus',function (Blueprint $table){
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
        Schema::dropIfExists('raspberrystatus');
    }
}

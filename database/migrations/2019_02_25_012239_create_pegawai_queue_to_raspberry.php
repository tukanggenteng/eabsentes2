<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaiQueueToRaspberry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('queue_pegawais',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('macaddress_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedInteger('instansi_id');
            $table->string('command');           
            $table->timestamps();
        });

        Schema::table('queue_pegawais',function (Blueprint $table){
            $table->foreign('macaddress_id')->references('id')->on('macaddresses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');            
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
        Schema::drop('queue_pegawais');
    }
}

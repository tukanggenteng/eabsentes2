<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAttTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('log_att_trans',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('macaddress_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedInteger('instansi_id');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('status');
            $table->boolean('flag');
            $table->boolean('expire');            
            $table->timestamps();
        });

        Schema::table('log_att_trans',function (Blueprint $table){
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
        Schema::drop('log_att_trans');

    }
}

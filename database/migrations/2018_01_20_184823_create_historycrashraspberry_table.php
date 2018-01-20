<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorycrashraspberryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('historycrashraspberrys',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('ip');
            $table->unsignedInteger('instansi_id');
            $table->longText('keterangan');
            $table->timestamps();
        });

        Schema::table('historycrashraspberrys',function (Blueprint $table){
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
        Schema::dropIfExists('historycrashraspberrys');
    }
}

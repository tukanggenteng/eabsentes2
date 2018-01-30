<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIjinpulangcepat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ijinpulangcepats',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
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
        Schema::dropIfExists('ijinpulangcepats');
    }
}

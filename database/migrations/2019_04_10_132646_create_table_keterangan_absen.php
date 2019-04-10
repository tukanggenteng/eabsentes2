<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableKeteranganAbsen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('keterangan_absens',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('jenisabsen_id');
            $table->date('tanggal');           
            $table->timestamps();
        });
        Schema::table('keterangan_absens',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jenisabsen_id')->references('id')->on('jenisabsens')->onDelete('no action')->onUpdate('no action');
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
        Schema::dropIfExists('keterangan_absens');
    }
}

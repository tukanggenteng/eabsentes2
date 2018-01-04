<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePegawaiHapusFinger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('hapusfingerpegawais',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->timestamps();
        });

        Schema::table('hapusfingerpegawais',function (Blueprint $table){
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('hapusfingerpegawais');
    }
}

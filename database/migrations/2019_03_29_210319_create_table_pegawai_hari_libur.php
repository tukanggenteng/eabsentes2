<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePegawaiHariLibur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pegawai_hari_liburs',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pegawai_hari_liburs',function (Blueprint $table){
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
        Schema::dropIfExists('pegawai_hari_liburs');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIstirahat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('istirahats',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->time('jam_ki');
            $table->time('jam_mi');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('istirahats',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('ijinpulangcepats',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('istirahats');
    }
}

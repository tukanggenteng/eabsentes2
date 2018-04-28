<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('finalrekapbulanans',function(Blueprint $table){
            $table->unsignedBigInteger('jadwalkerja_id')->nullable();
        });

        Schema::table('rekapbulanans',function(Blueprint $table){
            $table->unsignedBigInteger('jadwalkerja_id')->nullable();
        });

        Schema::table('masterbulanans',function(Blueprint $table){
            $table->unsignedBigInteger('jadwalkerja_id')->nullable();
            $table->integer('ijinpulangcepat');
        });

        Schema::table('finalrekapbulanans',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('rekapbulanans',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('masterbulanans',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
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
    }
}

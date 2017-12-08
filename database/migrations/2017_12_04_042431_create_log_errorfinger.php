<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogErrorfinger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('logfingererrors',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->string('nama');
            $table->timestamps();
        });

        Schema::table('logfingererrors',function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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

        Schema::dropIfExists('logfingererrors');
    }
}

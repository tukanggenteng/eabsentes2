<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRoleHariLiburs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('role_hari_liburs',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('hari_libur_id')->nullable();
            $table->date('tanggalberlakuharilibur');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('role_hari_liburs',function (Blueprint $table){
            $table->foreign('hari_libur_id')->references('id')->on('hari_liburs')->onDelete('SET NULL')->onUpdate('SET NULL');
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
        Schema::dropIfExists('role_hari_liburs');
    }
}

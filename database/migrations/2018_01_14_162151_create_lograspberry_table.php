<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLograspberryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //]
        Schema::create('lograspberrys',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('jumlahmac');
            $table->integer('jumlahpegawaifinger');
            $table->integer('jumlahadminfinger');
            $table->integer('jumlahabsensifinger');
            $table->integer('jumlahpegawailocal');
            $table->integer('jumlahadminlocal');
            $table->integer('jumlahabsensilocal');
            $table->string('alamatip');
            $table->string('versi');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::table('lograspberrys',function (Blueprint $table){
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
        Schema::dropIfExists('lograspberry');
    }
}

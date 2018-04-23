<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerawatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ruangans',function(Blueprint $table){
            $table->increments('id');
            $table->string('nama_ruangan');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('ruangans',function (Blueprint $table){
            // $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('perawatruangans',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedInteger('ruangan_id');
            $table->timestamps();
            $table->softDeletes();
        });

        

        Schema::table('perawatruangans',function (Blueprint $table){
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ruangan_id')->references('id')->on('ruangans')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('ruangans');
        Schema::dropIfExists('perawatruangans');
    }
}

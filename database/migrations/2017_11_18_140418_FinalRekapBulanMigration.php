<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FinalRekapBulanMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('finalrekapbulanans',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->date('periode');
            $table->unsignedBigInteger('pegawai_id');
            $table->integer('hari_kerja');
            $table->integer('hadir');
            $table->integer('tanpa_kabar');
            $table->integer('ijin');
            $table->integer('ijinterlambat');
            $table->integer('sakit');
            $table->integer('cuti');
            $table->integer('tugas_luar');
            $table->integer('tugas_belajar');
            $table->integer('terlambat');
            $table->integer('rapatundangan');
            $table->integer('pulang_cepat');
            $table->integer('persentase_apel');
            $table->integer('persentase_tidakhadir');
            $table->time('total_akumulasi');
            $table->time('total_terlambat');
            $table->unsignedInteger('instansi_id')->nullable();
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

        Schema::dropIfExists('finalrekapbulanans');
    }
}

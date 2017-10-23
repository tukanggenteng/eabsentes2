<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->unsignedInteger('instansi_id')->nullable();
            $table->unsignedInteger('role_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('instansis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode');
            $table->string('namaInstansi');
            $table->timestamps();
        });

        Schema::create('roles',function(Blueprint $table){
            $table->increments('id');
            $table->string('namaRole');
            $table->timestamps();
        });

        Schema::create('atts',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal_att')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->unsignedInteger('masukinstansi_id')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->unsignedInteger('keluarinstansi_id')->nullable();
            $table->time('akumulasi_sehari')->nullable();
            $table->unsignedBigInteger('jenisabsen_id');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->timestamps();
        });

        Schema::create('atts_trans',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal');
            $table->time('jam');
            $table->unsignedInteger('lokasi_alat');
            $table->boolean('status_kedatangan');
            $table->timestamps();
        });

        Schema::create('harikerjas',function(Blueprint $table){
            $table->increments('id');
            $table->string('hari');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('jadwalkerjas',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('jenis_jadwal');
            $table->time('jam_masukjadwal');
            $table->time('jam_keluarjadwal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
            $table->softDeletes();
        });

        //izinsakittl
        Schema::create('jenisabsens',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('jenis_absen');
            $table->timestamps();
        });

        Schema::create('rulejadwalpegawais',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->date('tanggal_awalrule');
            $table->date('tanggal_akhirrule');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->timestamps();
            $table->softDeletes();
        });

        //aturan jam absensi masuk atau keluar
        Schema::create('rulejammasuks',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->time('jamsebelum_masukkerja');
            $table->time('jamsebelum_pulangkerja');
            $table->unsignedBigInteger('jadwalkerja_id');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pegawais',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('nip');
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('jenispns')->nullable();
            $table->boolean('status_aktif')->nullable();
            $table->unsignedInteger('instansi_id')->nullable();
            $table->timestamps();
        });


        Schema::create('rekapbulanans',function(Blueprint $table){
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
            $table->unsignedInteger('instansi_id')->nullable();
            $table->timestamps();
        });

        Schema::create('masterbulanans',function(Blueprint $table){
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
            $table->unsignedInteger('instansi_id')->nullable();
            $table->timestamps();
        });

        Schema::create('tugasluars',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('tugasbelajars',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('sakits',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('cutis',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('ijinterlambats',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('ijins',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('rapatundangans',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rekapbulanan_id');
            $table->string('namafile');
            $table->integer('lama');
            $table->date('mulaitanggal');
            $table->unsignedInteger('instansi_id');
            $table->timestamps();
        });

        Schema::create('trigers',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('chats',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->mediumText('text');
            $table->timestamps();
        });

        Schema::create('fingerpegawais',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pegawai_id');
            $table->longText('size');
            $table->longText('valid');
            $table->longText('templatefinger');
            $table->timestamps();
        });

        Schema::table('users',function (Blueprint $table){
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('chats',function (Blueprint $table){
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('atts',function (Blueprint $table){
            $table->foreign('masukinstansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('keluarinstansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jenisabsen_id')->references('id')->on('jenisabsens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('atts_trans',function (Blueprint $table){
            $table->foreign('lokasi_alat')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('harikerjas',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('rulejadwalpegawais',function (Blueprint $table){
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('fingerpegawais',function (Blueprint $table){
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('rulejammasuks',function (Blueprint $table){
            $table->foreign('jadwalkerja_id')->references('id')->on('jadwalkerjas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('pegawais',function (Blueprint $table){
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('jadwalkerjas',function (Blueprint $table){
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::table('masterbulanans',function (Blueprint $table){
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('rekapbulanans',function (Blueprint $table){
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('tugasluars',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('tugasbelajars',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('sakits',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('cutis',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('ijinterlambats',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('rapatundangans',function (Blueprint $table){
            $table->foreign('rekapbulanan_id')->references('id')->on('rekapbulanans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('instansi_id')->references('id')->on('instansis')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('ijins',function (Blueprint $table){
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('instansis');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('atts_trans');
        Schema::dropIfExists('atts');
        Schema::dropIfExists('harikerjas');
        Schema::dropIfExists('jadwalkerjas');
        Schema::dropIfExists('jenisabsens');
        Schema::dropIfExists('rulejadwalpegawais');
        Schema::dropIfExists('rulejammasuks');
        Schema::dropIfExists('pegawais');
        Schema::dropIfExists('transaksiabsens');
        Schema::dropIfExists('rekapbulanans');
        Schema::dropIfExists('tugasluars');
        Schema::dropIfExists('tugasbelajars');
        Schema::dropIfExists('sakits');
        Schema::dropIfExists('cutis');
        Schema::dropIfExists('ijins');
        Schema::dropIfExists('rapatundangan');
    }
}

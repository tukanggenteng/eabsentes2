<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKeteranganMasukIdKeteranganKeluarIdToAtt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('atts',function(Blueprint $table){
            $table->unsignedBigInteger('keteranganmasuk_id')->nullable();
            $table->unsignedBigInteger('keterangankeluar_id')->nullable();
        });

        Schema::table('atts',function (Blueprint $table){
            $table->foreign('keteranganmasuk_id')->references('id')->on('jenisabsens')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('keterangankeluar_id')->references('id')->on('jenisabsens')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('atts',function(Blueprint $table){
            $table->dropForeign('atts_keteranganmasuk_id');
            $table->dropForeign('atts_keterangankeluar_id');
        });

        Schema::table('atts', function (Blueprint $table) {
            $table->dropColumn('keteranganmasuk_id');
            $table->dropColumn('keterangankeluar_id');
        });

    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddcolumApelRekapanTable extends Migration
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
            $table->integer('apelbulanan')->nullable();
        });

        Schema::table('rekapbulanans',function(Blueprint $table){
            $table->integer('apelbulanan')->nullable();
        });

        Schema::table('masterbulanans',function(Blueprint $table){
            $table->integer('apelbulanan')->nullable();
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFingerprintIpToQueuePegawais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('queue_pegawais',function(Blueprint $table){
            $table->string('fingerprint_ip');
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

        Schema::table('queue_pegawais', function (Blueprint $table) {
            $table->dropColumn('fingerprint_ip');
        });

    }
}

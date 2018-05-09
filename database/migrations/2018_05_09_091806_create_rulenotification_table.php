<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulenotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rulenotifications',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('notification_id');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('rulenotifications',function(Blueprint $table){
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('rulenotifications');
    }
}

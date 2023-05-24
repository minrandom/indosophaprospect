<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');// bussiness unit name in indosopha
            $table->unsignedBigInteger('head_user_id');
            $table->unsignedBigInteger('admin_user_id');
            $table->string('email_bu');

          //  $table->foreign('head_user_id')->references('id')->on('user');
          //  $table->foreign('admin_user_id')->references('id')->on('user');
          
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
        Schema::dropIfExists('units');
    }
}

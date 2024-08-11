<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_creator');
            $table->unsignedBigInteger('pic_user_id');
            $table->string('prospect_no');//number triggered
            $table->string('prospect_source');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('unit_id');//bussiness unit id
            $table->unsignedBigInteger('config_id');
            $table->integer('qty');
            $table->unsignedBigInteger('submitted_price');
            $table->date('eta_po_date');
            $table->integer('status');
            $table->dateTime('validation_time');
            $table->integer('validation_by');
            
            $table->timestamps();

          /*  $table->foreign('user_creator')->references('id')->on('user');
            $table->foreign('pic_user_id')->references('id')->on('user');
            $table->foreign('province_id')->references('id')->on('province');
            $table->foreign('hospital_id')->references('id')->on('hospital');
            $table->foreign('department_id')->references('id')->on('department');
            $table->foreign('unit_id')->references('id')->on('unit');
            $table->foreign('config_id')->references('id')->on('config');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prospects');
    }
}

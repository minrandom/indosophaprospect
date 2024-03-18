<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('task');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('department_id');
            $table->integer('status');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('create_for');
            $table->unsignedBigInteger('validation_by');
            $table->dateTime('validation_at');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->unsignedBigInteger('checkin_id');


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
        Schema::dropIfExists('schedules');
    }
}

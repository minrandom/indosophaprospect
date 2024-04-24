<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checkin_id');
            $table->unsignedBigInteger('user_id');
            $table->string('place_name');
            $table->string('address');
            $table->longText('check_in_loc')->nullable();
            $table->longText('photo_data')->nullable();
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
        Schema::dropIfExists('attendance_outs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduledDateToMissionRunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mission_runs', function (Blueprint $table) {
             $table->date('schedule_date')->nullable()->after('deadline_mission');
      $table->time('schedule_time')->nullable()->after('schedule_date'); // HH:MM:SS
      $table->unsignedSmallInteger('schedule_duration_minutes')->nullable()->after('schedule_time'); // 60,120,180...
      $table->unsignedTinyInteger('status')->default(1)->after('schedule_duration_minutes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mission_runs', function (Blueprint $table) {
            //
            $table->dropColumn(['schedule_date','schedule_time','schedule_duration_minutes','status']);
        });
    }
}

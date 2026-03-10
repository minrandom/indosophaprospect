<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
              // date + time slot (30 mins)

        $table->time('schedule_time')->nullable()->after('schedule_date'); // "08:00:00" etc

        // optional: how many minutes (default 30)
        $table->unsignedSmallInteger('schedule_duration_minutes')->default(30)->after('schedule_time');

        // helpful indexes for week queries and conflict checking
        $table->index(['schedule_date', 'schedule_time'], 'missions_schedule_dt_idx');
        $table->index(['pic_user_id', 'schedule_date', 'schedule_time'], 'missions_pic_schedule_idx');
        $table->index(['hospital_id', 'schedule_date', 'schedule_time'], 'missions_hospital_schedule_idx');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('missions', function (Blueprint $table) {
            $table->dropIndex('missions_schedule_dt_idx');
            $table->dropIndex('missions_pic_schedule_idx');
            $table->dropIndex('missions_hospital_schedule_idx');
            $table->dropColumn([ 'schedule_time', 'schedule_duration_minutes']);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartsFieldToMissionRunTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mission_runs', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('mission_runs', 'started_at')) {
        $table->timestamp('started_at')->nullable()->after('status_mission');
      }
      if (!Schema::hasColumn('mission_runs', 'started_by')) {
        $table->unsignedBigInteger('started_by')->nullable()->after('started_at');
        }
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
          if (Schema::hasColumn('mission_runs', 'started_by')) $table->dropColumn('started_by');
      if (Schema::hasColumn('mission_runs', 'started_at')) $table->dropColumn('started_at');
    });
    }
}

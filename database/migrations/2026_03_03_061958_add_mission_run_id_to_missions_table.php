<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissionRunIdToMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
           $table->unsignedBigInteger('mission_run_id')->nullable()->after('id');
            $table->index('mission_run_id');
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
            //
            $table->dropIndex(['mission_run_id']);
            $table->dropColumn('mission_run_id');
        });
    }
}

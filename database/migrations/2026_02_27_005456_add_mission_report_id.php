<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissionReportId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('missions', function (Blueprint $table) {
        $table->unsignedBigInteger('mission_report_id')->nullable()->after('report_result');
        $table->index(['mission_report_id']);
        // FK after column exists
        });

        Schema::table('missions', function (Blueprint $table) {
        $table->foreign('mission_report_id')
            ->references('id')->on('mission_reports')
            ->nullOnDelete();
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
            $table->dropForeign(['mission_report_id']);
            $table->dropIndex(['mission_report_id']);
            $table->dropColumn('mission_report_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalFieldsToMissionRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mission_runs', function (Blueprint $table) {
            $table->tinyInteger('is_approve')->default(0)->after('status');
            // 0 = waiting approval, 1 = approved, -1 = rejected


            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->dateTime('approved_at')->nullable()->after('approved_by');
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
        });
    }
}

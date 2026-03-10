<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_reports', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('mission_id')->unique();
            $table->unsignedBigInteger('reporter_user_id')->nullable(); // who submitted report

            // store report in structured JSON (flexible per task_reference)
            $table->json('payload')->nullable();

            // quick summary text for list view
            $table->string('summary', 255)->nullable();

            $table->timestamps();

            $table->foreign('mission_id')->references('id')->on('missions')->cascadeOnDelete();
            $table->foreign('reporter_user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['mission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mission_reports');
    }
}

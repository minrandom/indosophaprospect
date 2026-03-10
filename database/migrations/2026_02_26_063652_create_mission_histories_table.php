<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_histories', function (Blueprint $table) {
               $table->bigIncrements('id');

            $table->unsignedBigInteger('mission_id');
            $table->unsignedBigInteger('actor_user_id')->nullable(); // who did the change (auth user)

            // action type, useful for filtering timeline
            $table->string('action', 50);
            // examples: created, moved_to_mission_pool, scheduled, updated, canceled, done, add_report, etc.

            // Store diffs (before/after)
            $table->json('changes')->nullable();
            // format example:
            // {
            //   "status_mission": {"from": 0, "to": 1},
            //   "pic_user_id": {"from": null, "to": 15},
            //   "deadline": {"from": "2026-02-26", "to": "2026-03-10"}
            // }

            $table->text('note')->nullable(); // optional human note
            $table->timestamps();

            $table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');
            $table->foreign('actor_user_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['mission_id', 'created_at']);
            $table->index(['actor_user_id', 'created_at']);
            $table->index(['action']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mission_histories');
    }
}

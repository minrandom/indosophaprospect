<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_runs', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();

            $table->unsignedBigInteger('hospital_id');          // required
            $table->unsignedBigInteger('creator_id');           // required (auth user)

            $table->date('deadline_mission')->nullable();

            // 1 mission header status (separate from task status)
            // 0=draft, 1=active, 2=scheduled, 3=on_progress, 4=cancel, 5=done, -1=missed
            $table->integer('status_mission')->default(0);

            $table->boolean('validate_mission')->default(false);

            $table->unsignedBigInteger('person_in_charge')->nullable(); // PIC (user id)

            $table->unsignedBigInteger('check_in_id')->nullable();
            $table->unsignedBigInteger('check_out_id')->nullable();

            $table->timestamps();

            $table->index(['hospital_id', 'status_mission']);
            $table->index(['creator_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mission_runs');
    }
}

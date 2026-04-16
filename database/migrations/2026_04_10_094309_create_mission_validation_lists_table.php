<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissionValidationListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mission_validation_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mission_id');
            $table->string('task_ref', 100)->nullable();
            $table->string('code_ref', 100)->nullable();
            $table->longText('payload_form')->nullable();
            $table->unsignedBigInteger('validate_by')->nullable();
            $table->timestamp('validate_at')->nullable();
            $table->tinyInteger('status')->default(0); // 0=new/on-review, 1=done
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
        Schema::dropIfExists('mission_validation_lists');
    }
}

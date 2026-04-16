<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallbaseUpdateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installbase_update_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installbase_id');
            $table->unsignedBigInteger('mission_id')->nullable(); // task id
            $table->string('task_update_no')->nullable();
            $table->string('field_column');
            $table->longText('value_before')->nullable();
            $table->longText('new_value')->nullable();
            $table->unsignedBigInteger('updated_by');
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
        //
        Schema::dropIfExists('installbase_update_logs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuccessReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('success_reqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospect_id');
            $table->unsignedBigInteger('request_by');
            $table->string('request_date');
            $table->string('request_reason');
            $table->text('keterangan');
            $table->string('validation_time')->nullable();
            $table->tinyInteger('validation_status')->default(0);
            $table->integer('isBuNoted')->default(0);
            $table->string('bu_noted_at')->nullable();
            $table->unsignedBigInteger('validation_by')->nullable();
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
        Schema::dropIfExists('success_reqs');
    }
}

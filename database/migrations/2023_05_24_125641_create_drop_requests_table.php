<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drop_requests', function (Blueprint $table) {
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

           // $table->foreign('prospect_id')->references('id')->on('prospect');
           // $table->foreign('request_by')->references('id')->on('user');
           // $table->foreign('validation_by')->references('id')->on('user');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drop_requests');
    }
}

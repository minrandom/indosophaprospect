<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_id');
            $table->date('log_date')->nullable();
            $table->string('col_update');
            $table->string('col_before');
            $table->string('col_after');
            $table->string('update_at_lat');
            $table->string('update_at_long');
            $table->unsignedBigInteger('updated_by');
            $table->data('approve_at');
            $table->unsignedBigInteger('approve_by');
            
            $table->timestamps();



          //  $table->foreign('review_id')->references('id')->on('review');
           // $table->foreign('updated_by')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review_logs');
    }
}

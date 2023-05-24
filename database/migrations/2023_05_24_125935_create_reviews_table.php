<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospect_id');
            $table->date('first_offer_date')->nullable();
            $table->date('demo_date')->nullable();
            $table->date('presentation_date')->nullable();
            $table->date('last_offer_date')->nullable();
            $table->string('user_status')->nullable();
            $table->string('direksi_status')->nullable();
            $table->string('purchasing_status')->nullable();
            $table->string('anggaran_status')->nullable();
            $table->string('jenis_anggaran')->nullable();
            $table->string('chance')->nullable();
            $table->string('comment')->nullable();
            $table->string('next_action')->nullable();
            $table->timestamps();

           // $table->foreign('prospect_id')->references('id')->on('prospect');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}

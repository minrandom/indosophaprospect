<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('principal_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('act_as');
            $table->string('times_extended');
            $table->string('agreement_code');
            $table->timestamps();


           // $table->foreign('principal_id')->references('id')->on('principal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agreements');
    }
}

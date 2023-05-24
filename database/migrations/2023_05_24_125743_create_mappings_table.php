<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mappings', function (Blueprint $table) {
            $table->id();
            $table->string('status_mapping');
            $table->string('quetioner_link');
            $table->unsignedBigInteger('prospect_id');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('department_id');
            $table->timestamps();

           // $table->foreign('prospect_id')->references('id')->on('prospect');
           // $table->foreign('hospital_id')->references('id')->on('hospital');
           // $table->foreign('department_id')->references('id')->on('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mappings');
    }
}

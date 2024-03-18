<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProspectRemarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospect_remarks', function (Blueprint $table) {
        
                $table->id();
                $table->unsignedBigInteger('prospect_id');
                $table->string('type');
                $table->unsignedBigInteger('creator');
                $table->string('creator_role');
                $table->integer('column_target');
                $table->text('messages');
                $table->integer('status');
    
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
        Schema::dropIfExists('prospect_remarks');
        //
    }
}

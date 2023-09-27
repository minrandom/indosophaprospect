<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('unit_id');
            $table->string('config_code');
            $table->unsignedBigInteger('brand_id');
            $table->string('category');
            $table->string('genre');//unit,option,acc,set
            $table->string('type');//type item from unit
            $table->string('uom');//set,box,package
            $table->string('consist_of')->nullable();
            
            $table->integer('price_include_ppn');
            $table->timestamps();

           // $table->foreign('unit_id')->references('id')->on('unit');
           // $table->foreign('brand_id')->references('id')->on('brand');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('longname');//employee long name
            $table->string('sex');//m-f
            $table->string('birthplace')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('nik');
            $table->string('marital_status')->nullable();
            $table->longText('address')->nullable();
            $table->string('position');
            $table->string('area');//national,east,west,ho
            $table->date('join_date')->nullable();

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
        Schema::dropIfExists('employees');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorForecastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_forecasts', function (Blueprint $table) {
      
                $table->id();
                $table->unsignedBigInteger('user_creator');
                $table->string('forecast_no')->nullable();//number triggered
                $table->string('tempCode');//number triggered          
                $table->unsignedBigInteger('province_id');
                $table->unsignedBigInteger('hospital_id');
                $table->unsignedBigInteger('department_id');
                $table->unsignedBigInteger('unit_id');//bussiness unit id
                $table->unsignedBigInteger('category_id');//category
                $table->text('config_id');
                $table->text('qty');
                $table->unsignedBigInteger('submitted_total_price');
                $table->string('po_target');
                $table->string('payment_method');
                $table->integer('status')->default(0);
                $table->text('comment')->nullable();
                $table->unsignedBigInteger('result_id')->nullable();
   
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
        Schema::dropIfExists('vendor_forecasts');
    }
}

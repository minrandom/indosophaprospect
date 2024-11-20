<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorForecastResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_forecast_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_forecast_id');
            $table->string('po_date')->nullable();
            $table->text('result_config_id')->nullable();
            $table->text('result_qty')->nullable();
            $table->unsignedBigInteger('result_value')->nullable();
         
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
        Schema::dropIfExists('vendor_forecast_results');
    }
}

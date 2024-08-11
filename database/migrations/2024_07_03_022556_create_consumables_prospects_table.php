<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumablesProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumables_prospects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_creator');
            $table->unsignedBigInteger('pic_user_id')->nullable();
            $table->string('prospect_no')->nullable();//number triggered
            $table->string('tempCode');//number triggered
            $table->string('prospect_source');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('unit_id');//bussiness unit id
            $table->unsignedBigInteger('category_id');//category
            $table->text('config_id');
            $table->text('qty');
            $table->unsignedBigInteger('submitted_total_price');
            $table->string('po_target');
            $table->date('eta_po_date')->nullable();
            $table->integer('status')->default(0);
            $table->dateTime('validation_time')->nullable();
            $table->integer('validation_by')->nullable();
            
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
        Schema::dropIfExists('consumables_prospects');
    }
}

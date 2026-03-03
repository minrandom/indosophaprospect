<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallbasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installbases', function (Blueprint $table) {
            $table->id();
            $table->string('installbase_code')->unique();
            $table->unsignedBigInteger('product_id');
            $table->string('serial_number')->nullable();
            $table->unsignedBigInteger('hospital_id');
            $table->string('department')->nullable();
            $table->string('department_phone')->nullable();
            $table->string('equipment_location')->nullable();
            $table->string('pic_to_recall')->nullable();
            $table->string('pic_number')->nullable();
            $table->date('installation_date')->nullable();
            $table->string('installbase_status')->nullable();
            $table->string('repair_status')->nullable();
            $table->string('maintenance_status')->nullable();
            $table->string('end_of_warranty')->nullable();
            $table->string('service_contract')->nullable();
            $table->string('type_of_contract')->nullable();
            $table->string('end_of_service')->nullable();
            $table->date('last_review')->nullable();
            $table->string('note_last_review')->nullable();
            $table->string('so_number')->nullable();

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
        Schema::dropIfExists('installbases');
    }
}

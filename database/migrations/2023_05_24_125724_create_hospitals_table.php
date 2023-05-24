<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('code');//hospital code from ISSAPP
            $table->string('name');
            $table->unsignedBigInteger('province_id');
            $table->string('city');//hospital code from ISSAPP
            $table->string('city_order_no');//get from kemendagri
            $table->string('category')->nullable();//small,medium,major,large
            $table->longText('address')->nullable();;
            $table->string('ownership');//swasta/negeri
            $table->string('owned_by')->nullable();; //PT,yayasan, TNI ad dll
            $table->string('class')->nullable();;//A/b/C
            $table->string('akreditas')->nullable();;//tingkat utama, paripurna dll
            $table->timestamps();

          
           // $table->foreign('province_id')->references('id')->on('province');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospitals');
    }
}

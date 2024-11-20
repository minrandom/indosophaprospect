<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeptVendorListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dept_vendor_lists')) {
            Schema::create('dept_vendor_lists', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hospital_id');
                $table->unsignedBigInteger('department_id');
                $table->date('last_transaction_date');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dept_vendor_lists');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlagNeedUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('installbases', function (Blueprint $table) {
            //
            $table->string('need_update')->nullable()->default(1)->after('so_number');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('installbases', function (Blueprint $table) {
            //
            $table->dropColumn('need_update');
        });
    }
}

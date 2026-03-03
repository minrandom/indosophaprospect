<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPicJobTitle extends Migration
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
                $table->string('pic_job_title')->nullable()->after('pic_to_recall');
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
            $table->dropColumn('pic_job_title');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadFieldToProspectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->string('entry_type', 20)->nullable()->after('prospect_no'); // lead / prospect
            $table->text('added_info')->nullable()->after('prospect_source');
            $table->string('promo_no')->nullable()->after('prospect_no');
            $table->date('lead_due_date')->nullable()->after('validation_by');
            $table->date('auto_drop_at')->nullable()->after('lead_due_date');
        });
    }

    public function down()
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropColumn([
                'entry_type',
                'added_info',
                'promo_no',
                'lead_due_date',
                'auto_drop_at',
            ]);
        });
    }
}

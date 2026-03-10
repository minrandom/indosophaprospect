<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FillMissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('missions', function (Blueprint $table) {

            // Add columns if you forgot them before (use ->after() if you want)
            if (!Schema::hasColumn('missions', 'code')) {
                $table->string('code', 120)->nullable()->unique();
            }

            if (!Schema::hasColumn('missions', 'hospital_id')) {
                $table->unsignedBigInteger('hospital_id');
                $table->index('hospital_id');
            }

            if (!Schema::hasColumn('missions', 'department')) {
                $table->string('department', 255)->nullable();
            }

            if (!Schema::hasColumn('missions', 'pic_user_id')) {
                $table->unsignedBigInteger('pic_user_id')->nullable();
                $table->index('pic_user_id');
            }

            if (!Schema::hasColumn('missions', 'user_to_meet')) {
                $table->string('user_to_meet', 255)->nullable();
            }

            if (!Schema::hasColumn('missions', 'code_ref')) {
                $table->string('code_ref', 255)->nullable();
            }

            if (!Schema::hasColumn('missions', 'task_reference')) {
                    $table->enum('task_reference', ['installbase','prospect','mapping','finance','technical','custom'])->default('custom');
                    $table->index('task_reference');
            }


            if (!Schema::hasColumn('missions', 'task_creator_id')) {
                $table->unsignedBigInteger('task_creator_id');
                $table->index('task_creator_id');
            }

            if (!Schema::hasColumn('missions', 'deadline')) {
                $table->date('deadline')->nullable();
                $table->index('deadline');
            }

            if (!Schema::hasColumn('missions', 'priority_level')) {
                $table->enum('priority_level', ['Super Urgent', 'Urgent', 'Penting'])->default('Penting');
                $table->index('priority_level');
            }

            if (!Schema::hasColumn('missions', 'generate_task_via')) {
                $table->enum('generate_task_via', ['custom_task','installbase_menu','prospect_menu','mapping_menu','finance_generate','technical_generate'])->default('custom_task');
                $table->index('generate_task_via');
            }

            if (!Schema::hasColumn('missions', 'expected_outcome')) {
                $table->text('expected_outcome')->nullable(); // temporary nullable if old rows exist
            }

            if (!Schema::hasColumn('missions', 'report_result')) {
                $table->text('report_result')->nullable();
            }

            if (!Schema::hasColumn('missions', 'status_mission')) {
                $table->integer('status_mission')->default(0);
                $table->index('status_mission');
            }

            if (!Schema::hasColumn('missions', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->index('updated_by');
            }
        });

        // ===== ENUM updates using raw SQL (MySQL) =====
        // Add business_unit to task_reference enum
        // And allow your generate_task_via values if needed
        // Change column names below IF your table uses different names.

        // task_reference enum update (add business_unit)
        if (Schema::hasColumn('missions', 'task_reference')) {
            DB::statement("
                ALTER TABLE missions
                MODIFY task_reference ENUM(
                    'installbase','prospect','mapping','finance','technical','custom','business_unit'
                ) NOT NULL
            ");
        }

        // generate_task_via enum update (keep your list)
        if (Schema::hasColumn('missions', 'generate_task_via')) {
            DB::statement("
                ALTER TABLE missions
                MODIFY generate_task_via ENUM(
                    'custom_task','installbase_menu','prospect_menu','mapping_menu',
                    'finance_generate','technical_generate','bu_generate'
                ) NOT NULL
            ");
        }
    }

    public function down(): void
    {
        // Usually you don't fully revert enum changes safely.
        // But you can drop added columns if you want.
        Schema::table('missions', function (Blueprint $table) {
            // dropColumn only if you added them, optional
        });
    }
};

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Installbase;
use App\Models\mission;
use Carbon\Carbon;
use DB;

class GenerateInstallbaseMissingTasks extends Command
{
    protected $signature = 'installbase:generate-missing-tasks {--dry-run}';
    protected $description = 'Generate installbase update tasks based on missing fields';

    public function handle()
    {
        $fieldsToCheck = [
            'department_id'        => 'Department',
            'pic_to_recall'        => 'PIC to Recall',
            'department_phone'     => 'Department Phone',
            'serial_number'        => 'Serial Number',
            'installation_date'    => 'Installation Date',
            'installbase_status'   => 'Installation Status',
            'end_of_warranty'      => 'End Of Warranty',
        ];

        $installbases = Installbase::with(['hospital', 'product'])
            ->whereNotNull('hospital_id')
            ->get();

        $this->info('Installbase checked: ' . $installbases->count());

        $created = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            foreach ($installbases as $ib) {

                $missing = [];

                foreach ($fieldsToCheck as $field => $label) {
                    $value = $ib->{$field} ?? null;

                    if (is_null($value) || $value === '' || $value === '0000-00-00') {
                        $missing[] = $label;
                    }
                }

                if (count($missing) < 1) {
                    $skipped++;
                    continue;
                }

                $exists = mission::where('task_reference', 'installbase')
                    ->where('code_ref', $ib->id)
                    ->whereIn('status_mission', [0,1,2,3,4,6,7])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                $missingText = implode(', ', $missing);

                if ($this->option('dry-run')) {
                    $this->line(
                        "IB ID: {$ib->id} | " .
                        "Code: " . ($ib->installbase_code ?? '-') . " | " .
                        "Hospital: " . ($ib->hospital->name ?? '-') . " | " .
                        "Missing: {$missingText}"
                    );
                    $created++;
                    continue;
                }

                mission::create([
                    'code' => mission::makeCode('installbase'),
                    'hospital_id' => $ib->hospital_id,
                    'department' => $ib->department_id,
                    'pic_user_id' => null,
                    'user_to_meet' => null,
                    'code_ref' => $ib->id,
                    'task_reference' => 'installbase',
                    'task_purpose' => 'Fill missing installbase data: ' . $missingText,
                    'task_creator_id' => 1,
                    'generate_task_via' => 'generated_by_system',
                    'deadline' => Carbon::today()->addWeeks(2)->toDateString(),
                    'priority_level' => 'Urgent',
                    'expected_outcome' => 'Complete missing installbase fields: ' . $missingText,
                    'report_result' => null,
                    'status_mission' => 0,
                    'updated_by' => null,
                ]);

                $created++;
            }

            if ($this->option('dry-run')) {
                DB::rollBack();
                $this->info("Dry run finished. Tasks that would be created: {$created}. Skipped: {$skipped}.");
                return Command::SUCCESS;
            }

            DB::commit();

            $this->info("Done. Created: {$created}. Skipped: {$skipped}.");

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}

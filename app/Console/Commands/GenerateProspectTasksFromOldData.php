<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prospect;
use App\Models\mission;
use Carbon\Carbon;
use DB;

class GenerateProspectTasksFromOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
  protected $signature = 'prospects:generate-tasks-old-data {--dry-run}';
    protected $description = 'Generate prospect tasks from old prospect data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDate = '2025-01-01';
        $endDate   = '2026-04-10'; // before 10 Apr 2026
        $today     = Carbon::today()->toDateString();

        $prospects = Prospect::with(['review', 'hospital'])
        ->whereHas('review', function ($q) {
            $q->where('chance', '>', 0)
            ->where('chance', '<', 1);
        })
        ->where(function ($q) use ($startDate, $endDate, $today) {
            $q->whereBetween('created_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ])
            ->orWhereDate('eta_po_date', '>', $today);
        })
        ->get();

        $this->info('Matched prospects: ' . $prospects->count());

        if ($this->option('dry-run')) {
            $prospects->each(function ($p) {
                $this->line("Prospect ID: {$p->id} | Hospital: " . ($p->hospital->name ?? '-') . " | ETA: {$p->eta_po_date} | Chance: " . ($p->review->chance ?? '-')."| Temperature : " . ($p->temperature->tempName ?? '-') );
            });

            return Command::SUCCESS;
        }

        DB::beginTransaction();

        try {
            foreach ($prospects as $prospect) {

                // avoid duplicate task
                $exists = mission::where('task_reference', 'prospect')
                    ->where('code_ref', $prospect->id)
                    ->whereIn('status_mission', [0, 1, 2, 3, 4, 6, 7])
                    ->exists();

                if ($exists) {
                    continue;
                }

                mission::create([
                    'code' => mission::makeCode('prospect'),
                    'hospital_id' => $prospect->hospital_id,
                    'department' => $prospect->department_id,
                    'pic_user_id' => $prospect->pic_user_id,
                    'user_to_meet' => null,
                    'code_ref' => $prospect->id,
                    'task_reference' => 'prospect',
                    'task_purpose' => 'Follow up old prospect data',
                    'task_creator_id' => auth()->id() ?? 1,
                    'generate_task_via' => 'generated_by_system',
                    'deadline' => Carbon::today()->addWeeks(2)->toDateString(),
                    'priority_level' => 'URGENT',
                    'expected_outcome' => 'Update prospect review and next action',
                    'report_result' => null,
                    'status_mission' => 0,
                    'updated_by' => null,
                ]);
            }

            DB::commit();

            $this->info('Prospect tasks generated successfully.');

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\mission;
use App\Models\mission_report;
use App\Models\MissionHistory;
use Illuminate\Http\Request;

class MissionReportController extends Controller
{
    //

    private function logMissionChange(int $missionId, string $action, array $changes = [], ?string $note = null): void
    {
        MissionHistory::create([
            'mission_id' => $missionId,
            'actor_user_id' => auth()->id(),
            'action' => $action,
            'changes' => $changes ?: null,
            'note' => $note,
        ]);
    }

    public function form(Mission $mission)
    {
        // must be on progress to report (3)
        if ((int)$mission->status_mission !== 3) {
            // optional: allow view report form anyway
            // return redirect()->back()->with('error', 'Mission is not in progress.');
        }

        return view('admin.report_form', compact('mission'));
    }

    public function submit(Request $request, mission $mission)
    {
        // Validate based on task_reference
        $taskRef = $mission->task_reference;

        // Basic required for all
        $request->validate([
            'summary' => ['required','string','max:255'],
        ]);

        // Payload validation per task_ref (simple & flexible)
        if ($taskRef === 'installbase') {
            $request->validate([
                'ib_updates' => ['required','array','min:1'],
                'ib_updates.*.field' => ['required','string','max:100'],
                'ib_updates.*.value' => ['nullable','string','max:255'],
                'ib_updates.*.note' => ['nullable','string','max:255'],
            ]);
        } elseif ($taskRef === 'prospect') {
            $request->validate([
                'prospect_notes' => ['required','string'],
            ]);
        } else {
            $request->validate([
                'generic_report' => ['required','string'],
            ]);
        }

        $before = $mission->only(['status_mission','mission_report_id']);

        // Build payload
        $payload = [
            'task_reference' => $taskRef,
        ];

        if ($taskRef === 'installbase') {
            $payload['ib_updates'] = $request->input('ib_updates');
        } elseif ($taskRef === 'prospect') {
            $payload['prospect_notes'] = $request->input('prospect_notes');
        } else {
            $payload['generic_report'] = $request->input('generic_report');
        }

        // Create/replace report (1-1)
        $report = mission_report::updateOrCreate(
            ['mission_id' => $mission->id],
            [
                'reporter_user_id' => auth()->id(),
                'summary' => $request->input('summary'),
                'payload' => $payload,
            ]
        );

        // link to missions
        $mission->mission_report_id = $report->id;

        // DONE after submit
        $mission->status_mission = 5;
        $mission->updated_by = auth()->id();
        $mission->save();

        $this->logMissionChange($mission->id, 'report_submitted', [
            'mission_report_id' => ['from' => $before['mission_report_id'], 'to' => $report->id],
            'status_mission' => ['from' => $before['status_mission'], 'to' => 5],
        ], $request->input('summary'));

        return redirect()
            ->route('missions.pool')
            ->with('success', 'Report submitted. Mission marked as DONE.');
    }

}

<div class="mb-3">
    <div><b>Task Code:</b> {{ $task->code ?? '-' }}</div>
    <div><b>Source - Group:</b> {{ strtoupper($task->task_source_label) ?? ($task->generate_task_via ?? '-') }} - <span class="text-info">{{ strtoupper($task->task_reference) ?? '-' }}</span></div>

    <div><b>Hospital:</b> {{ $task->hospital->name ?? '-' }}</div>
    <div><b>Department:</b> {{ $task->departmentRelation->name ?? '-' }}</div>
    <div><b>PIC:</b> {{ $task->picUser->name ?? '-' }}</div>
    <div><b>User to meet:</b> <span class="text-warning">{{ $task->user_to_meet ?? '-' }}</span></div>
    <div><b>Purpose:</b> {{ $task->task_purpose ?? '-' }}</div>
    <div><b>Expected Outcome:</b> {{ $task->expected_outcome ?? '-' }}</div>
    <div><b>Deadline:</b> {{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d-M-y') : '-' }}</div>
</div>

<hr>

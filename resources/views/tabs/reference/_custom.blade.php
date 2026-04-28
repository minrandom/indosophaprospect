
<div>
  <div><b>Task Code:</b> {{ $task->code }}</div>
  <div><b>Purpose:</b> {{ $task->task_purpose ?? '-' }}</div>
  <div><b>Expected Outcome:</b> {{ $task->expected_outcome ?? '-' }}</div>
  <div><b>Report:</b> {{ $task->report_result ?? '-' }}</div>
</div>

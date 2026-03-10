<div class="mb-2">
  <div class="small text-muted">
    Mission: <b>{{ $run->code ?? ('RUN-'.$run->id) }}</b> |
    Hospital: <b>{{ $run->hospital?->name ?? '-' }}</b> |
    Deadline: <b>{{ $run->deadline_mission ? \Carbon\Carbon::parse($run->deadline_mission)->format('d-M-y') : '-' }}</b>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-sm table-bordered mb-0">
    <thead class="thead-light text-uppercase small">
      <tr>
        <th>Task Code</th>
        <th>Purpose</th>
        <th>Reference</th>
        <th>Department</th>
        <th>Priority</th>
        <th>Deadline</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($tasks as $t)
        <tr>
          <td class="font-weight-bold">{{ $t->code }}</td>
          <td>{{ $t->task_purpose ?? '-' }}</td>
          <td class="text-uppercase">{{ $t->task_reference ?? '-' }}</td>
          <td>{{ $t->department ?? '-' }}</td>
          <td class="text-uppercase">{{ $t->priority_level ?? '-' }}</td>
          <td>{{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('d-M-y') : '-' }}</td>
          <td>
            <span class="badge badge-info">Unscheduled</span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center text-muted">No tasks in this mission</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

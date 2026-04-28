<div class="mb-2">
  <div class="small text-muted">
    Mission: <b>{{ $run->code ?? ('RUN-'.$run->id) }}</b> |
    Hospital: <b>{{ $run->hospital->name ?? '-' }}</b> |
    Schedule:
    <b>
      {{ !empty($run->schedule_date)
          ? \Carbon\Carbon::parse($run->schedule_date)->format('d-M-y')
          : '-' }}
    </b>
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
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      @forelse($tasks as $t)
        <tr>
          <td class="font-weight-bold">{{ $t->code }}</td>
          <td>{{ $t->task_purpose ?? '-' }}</td>
          <td class="text-uppercase">{{ $t->task_reference ?? '-' }}</td>
          <td>{{ $t->departmentRelation->name ?? '-' }}</td>
          <td class="text-uppercase">{{ $t->priority_level ?? '-' }}</td>
          <td>
            {{ !empty($t->deadline)
                ? \Carbon\Carbon::parse($t->deadline)->format('d-M-y')
                : '-' }}
          </td>
          <td>
            @if((int)$t->status_mission === 2)
              <span class="badge badge-warning">Scheduled</span>
            @elseif((int)$t->status_mission === 5)
              <span class="badge badge-success">Done</span>
            @elseif((int)$t->status_mission === 6)
              <span class="badge badge-primary">Submitted</span>
            @elseif((int)$t->status_mission === 7)
              <span class="badge badge-info">Validated</span>
            @else
              <span class="badge badge-secondary">Pending</span>
            @endif
          </td>
          <td>
            <button type="button"
                    class="btn btn-sm btn-outline-primary js-mission-ref"
                    data-id="{{ $t->id }}">
              Reference
            </button>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center text-muted">
            No tasks in this mission
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

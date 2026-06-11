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

@if($run->status < 6 && in_array(auth()->user()->role, ['am','nsm','admin']))
<button class="btn btn-sm btn-primary mb-2 js-open-add-task"
        data-run-id="{{ $run->id }}">
    + Add Task
</button>
@endif

<div class="table-responsive">
  <table class="table table-sm table-bordered mb-0">
    <thead class="thead-light text-uppercase small">
      <tr>
        <th>Department</th>
        <th>User to Meet</th>
        <th>Purpose</th>
        <th>Source/Group</th>
        <th>Priority</th>
        <th>Deadline</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>
      @forelse($tasks as $t)
        <tr>
          <td>{{ $t->departmentRelation->name ?? '-' }}</td>
          <td>{{ $t->user_to_meet ?? '-' }}</td>
          <td>{{ $t->task_purpose ?? '-' }}</td>
          <td class="text-uppercase">{{ $t->taskSourceLabel }} - <span class="text-info">{{ $t->task_reference ?? '-' }}</span></td>
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
            <button class="badge badge-primary js-view-task-detail"
                                        data-toggle="modal"
                                        data-target="#taskDetailModal"
                                        data-task-id="{{ $t->id }}">
                                    View Detail
                </button>

            @if($run->status < 6 && in_array(auth()->user()->role, ['am','nsm','admin']))
                @if((int)$t->status_mission < 6)
                    <button class="btn btn-sm btn-danger js-remove-task"
                            data-task-id="{{ $t->id }}">
                        Remove
                    </button>
                @endif
            @endif
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

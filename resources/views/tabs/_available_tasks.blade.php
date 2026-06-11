<table class="table table-sm table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Priority</th>
        <th>Department</th>
        <th>User to meet</th>
        <th>Purpose</th>
        <th>Source</th>
        <th>Expected Outcome</th>
        <th>Action</th>

    </tr>
    </thead>

    <tbody>
@if($tasks->isEmpty())
    <tr>
        <td colspan="8" class="text-center">No tasks available</td>
    </tr>
@endif
@foreach($tasks as $t)
<tr>
    <td>
        <input type="checkbox" class="js-task-checkbox" value="{{ $t->id }}">
    </td>
    <td>{{ $t->priority_level ?? '-' }}</td>
    <td>{{ $t->department ?? '-' }}</td>
    <td>{{ $t->user_to_meet ?? '-' }}</td>
    <td>{{ $t->task_purpose ?? '-' }}</td>
    <td><span class="text-uppercase">{{ $t->taskSourceLabel }} - <span class="text-info">{{ $t->task_reference }}</span></span></td>
    <td>{{ $t->expected_outcome }}</td>
    <td>
            <button class="badge badge-primary js-view-task-detail"
                                        data-toggle="modal"
                                        data-target="#taskDetailModal"
                                        data-task-id="{{ $t->id }}">
                                    View Detail
                </button></td>

</tr>
@endforeach
</table>

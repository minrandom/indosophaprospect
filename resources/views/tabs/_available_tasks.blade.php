<table class="table table-sm table-bordered">
    <thead>
    <tr>
        <th></th>
        <th>Source</th>
        <th>Purpose</th>
        <th>Expected Outcome</th>
        <th>Department</th>
    </tr>
    </thead>

    <tbody>
@foreach($tasks as $t)
<tr>
    <td>
        <input type="checkbox" class="js-task-checkbox" value="{{ $t->id }}">
    </td>
    <td><span class="text-uppercase">{{ $t->taskSourceLabel }} - <span class="text-info">{{ $t->task_reference }}</span></span></td>
    <td>{{ $t->task_purpose }}</td>
    <td>{{ $t->expected_outcome }}</td>
    <td>{{ $t->departmentRelation->name ?? '-' }}</td>
</tr>
@endforeach
</table>

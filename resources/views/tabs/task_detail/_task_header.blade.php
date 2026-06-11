<table class="table table-sm mb-3">
    <tbody>

        <tr>
            <td width="220"><b>Task Code</b></td>
            <td>{{ $task->code ?? '-' }}</td>
        </tr>

        <tr>
            <td><b>Source - Group</b></td>
            <td>
                {{ strtoupper($task->task_source_label ?? '-') }}
                -
                <span class="text-info">
                    {{ strtoupper($task->task_reference ?? '-') }}
                </span>
            </td>
        </tr>

        <tr>
            <td><b>Hospital</b></td>
            <td>{{ $task->hospital->name ?? '-' }}</td>
        </tr>

        <tr>
            <td><b>Department</b></td>
            <td>{{ $task->departmentRelation->name ?? '-' }}</td>
        </tr>

        <tr>
            <td><b>PIC</b></td>
            <td>{{ $task->picUser->name ?? '-' }}</td>
        </tr>

        <tr>
            <td><b>User to Meet</b></td>
            <td>
                <span class="text-warning">
                    {{ $task->user_to_meet ?? '-' }}
                </span>
            </td>
        </tr>

        <tr>
            <td><b>Purpose</b></td>
            <td>{{ $task->task_purpose ?? '-' }}</td>
        </tr>

        <tr>
            <td><b>Expected Outcome</b></td>
            <td>{{ $task->expected_outcome ?? '-' }}</td>
        </tr>

        <tr>
            <td><b>Deadline</b></td>
            <td>
                {{ $task->deadline
                    ? \Carbon\Carbon::parse($task->deadline)->format('d-M-y')
                    : '-' }}
            </td>
        </tr>



    </tbody>
</table>
<hr>

@php
    $showValue = function ($value) {
        if (is_null($value) || $value === '') {
            return 'Missing Data';
        }

        return $value;
    };
@endphp

<div class="mb-3">
    <div><b>Task Code:</b> {{ $task->code }}</div>
    <div><b>Task Ref:</b> {{ strtoupper($task->task_reference) }}</div>
    <div><b>Code Ref:</b> {{ $task->code_ref ?? '-' }}</div>
</div>

<form method="POST"
      action="{{ route('missions.task.validateTask', $task->id) }}"
      class="js-confirm-validate-task">
    @csrf

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th style="width:35%;">Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Task Purpose</td>
                    <td>{{ $showValue($task->task_purpose ?? null) }}</td>
                </tr>
                <tr>
                    <td>Expected Outcome</td>
                    <td>{{ $showValue($task->expected_outcome ?? null) }}</td>
                </tr>
                <tr>
                    <td>Report / Result</td>
                    <td>{{ $showValue($payload['report_result'] ?? $payload['generic_report'] ?? null) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group mt-3">
        <label class="small text-uppercase text-muted">Validator Comment</label>
        <textarea name="validator_comment" class="form-control" rows="3"></textarea>
    </div>

    <div class="text-right mt-3">
        <button type="submit" class="btn btn-primary">
            Validate
        </button>
    </div>
</form>

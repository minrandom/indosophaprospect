@php
    $fieldLabels = [
        'installbase_code'    => 'IB Code',
        'province'            => 'Province',
        'city'                => 'City',
        'hospital'            => 'Hospital',
        'department'          => 'Department',
        'pic_to_recall'       => 'PIC to Recall',
        'department_phone'    => 'Department Phone',
        'brand'               => 'Brand',
        'category'            => 'Category',
        'model_type'          => 'Model / Type',
        'serial_number'       => 'Serial Number',
        'installation_date'   => 'Installation Date',
        'installbase_status'  => 'Installation Status',
        'end_of_warranty'     => 'End Of Warranty',
        'report_result'       => 'Report / Notes',
    ];

    $displayValue = function ($key) use ($payload, $task) {
        $value = $payload[$key] ?? null;

        if (in_array($key, ['installbase_code', 'province', 'city', 'hospital', 'brand', 'category', 'model_type'])) {
            // these usually come from current installbase data, not editable payload
            return null;
        }

        if (is_null($value) || $value === '' || $value === '0000-00-00') {
            return 'Missing Data';
        }

        return $value;
    };
@endphp

<div class="mb-3">
    <div><b>Task Code:</b> {{ $task->code }}</div>
    <div><b>Task Ref:</b> {{ strtoupper($task->task_reference) }}</div>
    <div><b>Code Ref:</b> {{ $task->code_ref }}</div>
</div>

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
                <td>IB Code</td>
                <td>{{ $installbase->installbase_code ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Province</td>
                <td>{{ optional(optional($installbase->hospital)->province)->name ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>City</td>
                <td>{{ optional($installbase->hospital)->city ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Hospital</td>
                <td>{{ optional($installbase->hospital)->name ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Department</td>
                <td>{{ $payload['department'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>PIC to Recall</td>
                <td>{{ $payload['pic_to_recall'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Department Phone</td>
                <td>{{ $payload['department_phone'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Brand</td>
                <td>{{ optional(optional($installbase->product)->brand)->name ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Category</td>
                <td>{{ optional(optional($installbase->product)->category)->name ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Model / Type</td>
                <td>{{ optional($installbase->product)->model_type ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Serial Number</td>
                <td>{{ $payload['serial_number'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Installation Date</td>
                <td>{{ $payload['installation_date'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Installation Status</td>
                <td>{{ $payload['installbase_status'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>End Of Warranty</td>
                <td>{{ $payload['end_of_warranty'] ?? 'Missing Data' }}</td>
            </tr>
            <tr>
                <td>Report / Notes</td>
                <td>{{ $payload['report_result'] ?? 'Missing Data' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="text-right mt-3">
    <form method="POST" action="{{ route('missions.task.validateTask', $task->id) }}" class="d-inline js-confirm-validate-task">
        @csrf
        <button type="submit" class="btn btn-primary">Validate</button>
    </form>
</div>

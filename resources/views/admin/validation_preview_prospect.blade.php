@php
    $showValue = function ($value) {
        if (is_null($value) || $value === '' || $value === '0000-00-00') {
            return 'Missing Data';
        }

        return $value;
    };

    $stageName = optional($prospect->latestTemperature)->tempName ?? 'Missing Data';
    $lastNextAction = optional($prospect->review)->next_action ?? 'Missing Data';
@endphp

<div class="mb-3">
    <div><b>Task Code:</b> {{ $task->code }}</div>
    <div><b>Task Ref:</b> {{ strtoupper($task->task_reference) }}</div>
    <div><b>Code Ref:</b> {{ $task->code_ref }}</div>
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

            {{-- READ ONLY MASTER INFO --}}
            <tr>
                <td>Current Stage</td>
                <td>{{ $showValue($stageName) }}</td>
            </tr>
            <tr>
                <td>Prospect No</td>
                <td>{{ $showValue($prospect->prospect_no ?? null) }}</td>
            </tr>
            <tr>
                <td>Prospect Source</td>
                <td>{{ $showValue($prospect->prospect_source ?? null) }}</td>
            </tr>
            <tr>
                <td>Added Info</td>
                <td>{{ $showValue($prospect->added_info ?? null) }}</td>
            </tr>
            <tr>
                <td>Province</td>
                <td>{{ $showValue(optional(optional($prospect->hospital)->province)->name ?? null) }}</td>
            </tr>
            <tr>
                <td>Hospital</td>
                <td>{{ $showValue(optional($prospect->hospital)->name ?? null) }}</td>
            </tr>
            <tr>
                <td>Department</td>
                <td>{{ $showValue(optional($prospect->department)->name ?? null) }}</td>
            </tr>
            <tr>
                <td>Business Unit</td>
                <td>{{ $showValue(optional($prospect->unit)->name ?? null) }}</td>
            </tr>
            <tr>
                <td>Product</td>
                <td>{{ $showValue(optional($prospect->config)->name ?? null) }}</td>
            </tr>
            <tr>
                <td>Qty</td>
                <td>{{ $showValue($prospect->qty ?? null) }}</td>
            </tr>
            <tr>
                <td>Submitted Price</td>
                <td>{{ $showValue($prospect->submitted_price ?? null) }}</td>
            </tr>
            <tr>
                <td>ETA PO Date</td>
                <td>{{ $showValue($prospect->eta_po_date ?? null) }}</td>
            </tr>

            {{-- REVIEW PAYLOAD --}}
            <tr>
                <td>First Offer Date</td>
                <td>{{ $showValue($payload['first_offer_date'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Demo Date</td>
                <td>{{ $showValue($payload['demo_date'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Presentation Date</td>
                <td>{{ $showValue($payload['presentation_date'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Last Offer Date</td>
                <td>{{ $showValue($payload['last_offer_date'] ?? null) }}</td>
            </tr>
            <tr>
                <td>User Status</td>
                <td>{{ $showValue($payload['user_status'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Direksi Status</td>
                <td>{{ $showValue($payload['direksi_status'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Purchasing Status</td>
                <td>{{ $showValue($payload['purchasing_status'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Anggaran Status</td>
                <td>{{ $showValue($payload['anggaran_status'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Jenis Anggaran</td>
                <td>{{ $showValue($payload['jenis_anggaran'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Chance</td>
                <td>{{ $showValue($payload['chance'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Last Action</td>
                <td>
                    <span class="badge badge-info">
                        {{ $showValue($payload['next_action'] ?? null) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Comment</td>
                <td>{{ $showValue($payload['comment'] ?? null) }}</td>
            </tr>

            <tr>
                <td>Report / Notes</td>
                <td>{{ $showValue($payload['report_result'] ?? null) }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="text-right mt-3">


        <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="form-group">
                <label class="small text-muted">Next Action</label>
                <select name="next_action" id="next_action" class="form-control" required>
                    <option value="">Select Next Action</option>

                </select>
            </div>
            <div class="form-group">
                <label class="small text-muted">Validator Comment</label>
                <textarea name="validator_comment" class="form-control" rows="3" required></textarea>
            </div>



            <div class="text-right">
                <button type="submit" class="btn btn-success">
                    Confirm Validate
                </button>
            </div>

        </div>
    </div>
    </form>
</div>

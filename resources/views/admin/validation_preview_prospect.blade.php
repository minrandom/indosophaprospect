@php
    $showValue = function ($value) {
        if (is_null($value) || $value === '' || $value === '0000-00-00') {
            return 'Missing Data';
        }

        return $value;
    };

    $stageName = optional($prospect->temperature)->tempName ?? 'Missing Data';
    $lastNextAction = optional($prospect->review)->next_action ?? 'Missing Data';
    $actionType = $payload['action_type'] ?? 'prospect_update';

    $actionLabel = match ($actionType) {
        'lead_to_prospect' => 'Lead to Prospect',
        'promo_to_prospect' => 'Promo to Prospect',
        'drop' => 'Lead / Prospect to Drop',
        'promo' => 'Lead to Promo',
        'delayed' => 'Lead to Delayed',
        default => 'Prospect Update',
    };

    $oldReview = $prospect->review;

    $compareRows = [
        'Prospect No' => [
            'old' => $prospect->prospect_no ?? null,
            'new' => $prospect->prospect_no ?? null,
        ],
        'Current Stage' => [
            'old' => optional($prospect->temperature)->tempName,
            'new' => $actionLabel,
        ],
        'Business Unit' => [
            'old' => optional($prospect->unit)->name,
            'new' => optional($payloadUnit)->name ?? optional($prospect->unit)->name,
        ],

        'Product / Config' => [
            'old' => optional($prospect->config)->name ?? optional($prospect->config)->model_type,
            'new' => optional($payloadConfig)->name
                ?? optional($payloadConfig)->model_type
                ?? optional($prospect->config)->name
                ?? optional($prospect->config)->model_type,
        ],
        'ETA PO Date' => [
            'old' => $prospect->eta_po_date ?? null,
            'new' => !empty($payload['eta_po_date']) ? $payload['eta_po_date'] : ($prospect->eta_po_date ?? null),
        ],
        'First Offer Date' => [
            'old' => optional($oldReview)->first_offer_date,
            'new' => !empty($payload['first_offer_date']) ? $payload['first_offer_date'] : optional($oldReview)->first_offer_date,
        ],
        'Demo Date' => [
            'old' => optional($oldReview)->demo_date,
            'new' => !empty($payload['demo_date']) ? $payload['demo_date'] : optional($oldReview)->demo_date,
        ],
        'Presentation Date' => [
            'old' => optional($oldReview)->presentation_date,
            'new' => !empty($payload['presentation_date']) ? $payload['presentation_date'] : optional($oldReview)->presentation_date,
        ],
        'Last Offer Date' => [
            'old' => optional($oldReview)->last_offer_date,
            'new' => !empty($payload['last_offer_date']) ? $payload['last_offer_date'] : optional($oldReview)->last_offer_date,
        ],
        'User Status' => [
            'old' => optional($oldReview)->user_status,
            'new' => !empty($payload['user_status']) ? $payload['user_status'] : optional($oldReview)->user_status,
        ],
        'Direksi Status' => [
            'old' => optional($oldReview)->direksi_status,
            'new' => !empty($payload['direksi_status']) ? $payload['direksi_status'] : optional($oldReview)->direksi_status,
        ],
        'Purchasing Status' => [
            'old' => optional($oldReview)->purchasing_status,
            'new' => !empty($payload['purchasing_status']) ? $payload['purchasing_status'] : optional($oldReview)->purchasing_status,
        ],
        'Anggaran Status' => [
            'old' => optional($oldReview)->anggaran_status,
            'new' => !empty($payload['anggaran_status']) ? $payload['anggaran_status'] : optional($oldReview)->anggaran_status,
        ],
        'Jenis Anggaran' => [
            'old' => optional($oldReview)->jenis_anggaran,
            'new' => !empty($payload['jenis_anggaran']) ? $payload['jenis_anggaran'] : optional($oldReview)->jenis_anggaran,
        ],
        'Chance' => [
            'old' => optional($oldReview)->chance,
            'new' => isset($payload['chance']) && $payload['chance'] !== '' ? $payload['chance'] : optional($oldReview)->chance,
        ],
        'Comment' => [
            'old' => optional($oldReview)->comment,
            'new' => !empty($payload['comment']) ? $payload['comment'] : optional($oldReview)->comment,
        ],
        'Next Action' => [
            'old' => optional($oldReview)->next_action,
            'new' => !empty($payload['next_action']) ? $payload['next_action'] : optional($oldReview)->next_action,
        ],
        'Report / Notes' => [
            'old' => '-',
            'new' => $payload['report_result'] ?? null,
        ],
    ];
@endphp

<div class="mb-3">
    <div><b>Task Code:</b> {{ $task->code }}</div>
    <div><b>Task Ref:</b> {{ strtoupper($task->task_reference) }}</div>
    <div><b>Code Ref:</b> {{ $task->code_ref }}</div>
    <div><b>Action:</b> {{ $actionLabel }}</div>
</div>

<form method="POST"
      action="{{ route('missions.task.validateTask', $task->id) }}"
      class="js-confirm-validate-task">
    @csrf

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th style="width:28%;">Field</th>
                    <th style="width:36%;">Current Data</th>
                    <th style="width:36%;">Submitted Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compareRows as $label => $row)
                    @php
                        $oldVal = $showValue($row['old'] ?? null);
                        $newVal = $showValue($row['new'] ?? null);
                        $isChanged = trim((string)$oldVal) !== trim((string)$newVal);
                    @endphp

                    <tr>
                        <td class="font-weight-bold">{{ $label }}</td>
                        <td>{{ $oldVal }}</td>
                        <td class="{{ $isChanged ? 'font-weight-bold text-primary' : '' }}">
                            {{ $newVal }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-group mt-3">
        <label class="small text-uppercase text-muted">Validator Comment</label>
        <textarea name="validator_comment" class="form-control" rows="3" required></textarea>
    </div>

    @if(!in_array($actionType, ['drop', 'promo', 'delayed']))
        <div class="form-group">
            <label class="small text-uppercase text-muted">Next Action</label>
            <select name="next_action" class="form-control" required>
                <option value="">Select Next Action</option>
                <option value="Follow Up User">Follow Up User</option>
                <option value="Schedule Demo">Schedule Demo</option>
                <option value="Schedule Presentation">Schedule Presentation</option>
                <option value="Submit Offer">Submit Offer</option>
                <option value="Follow Up Purchasing">Follow Up Purchasing</option>
                <option value="Follow Up Direksi">Follow Up Direksi</option>
                <option value="Wait Anggaran Update">Wait Anggaran Update</option>
            </select>
        </div>
    @endif

    <div class="text-right mt-3">
        <button type="submit" class="btn btn-primary">Validate</button>
    </div>
</form>

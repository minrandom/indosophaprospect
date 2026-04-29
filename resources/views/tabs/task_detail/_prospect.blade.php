@include('tabs.task_detail._task_header')

@if(!$reference)
    <div class="alert alert-warning">Prospect reference not found.</div>
@else
    @php
        $stage = $reference->temperature->tempCodeName ?? null;
    @endphp

    <table class="table table-sm table-bordered">
        <tr>
            <th style="width:35%;">Stage</th>
            <td>{{ $reference->temperature->tempName ?? '-' }}</td>
        </tr>

        <tr>
            <th>Number</th>
            <td>
                @if($stage == 1)
                    <span class="text-warning">Update it to Promo / Prospect</span>
                @elseif($stage == 6)
                    {{ $reference->promo_no ?? '-' }}
                @else
                    {{ $reference->prospect_no ?? '-' }}
                @endif
            </td>
        </tr>
        <tr><th>Comment</th><td>{{ $reference->review->comment ?? '-' }}</td></tr>

        <tr><th>Source</th><td>{{ $reference->prospect_source ?? '-' }}</td></tr>
        <tr><th>Hospital</th><td>{{ $reference->hospital->name ?? '-' }}</td></tr>
        <tr><th>Province</th><td>{{ $reference->hospital->province->name ?? '-' }}</td></tr>
        <tr><th>Department</th><td>{{ $reference->department->name ?? '-' }}</td></tr>
        <tr><th>Business Unit</th><td>{{ $reference->unit->name ?? '-' }}</td></tr>
        <tr><th>Product</th><td>{{ $reference->config->name ?? $reference->config->model_type ?? '-' }}</td></tr>
        <tr><th>Qty</th><td>{{ $reference->qty ?? '-' }}</td></tr>
        <tr><th>ETA PO Date</th><td>{{ $reference->eta_po_date ? \Carbon\Carbon::parse($reference->eta_po_date)->format('d-M-y') : '-' }}</td></tr>
        <tr><th>Chance</th><td>{{ $reference->review->chance ?? '-' }}</td></tr>
        <tr><th>Next Action</th><td>{{ $reference->review->next_action ?? '-' }}</td></tr>
        <tr><th>Comment</th><td>{{ $reference->review->comment ?? '-' }}</td></tr>
    </table>
@endif

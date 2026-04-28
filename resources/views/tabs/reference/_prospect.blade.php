@if(!$prospect)
  <div class="alert alert-warning">Prospect reference not found.</div>
@else
  <table class="table table-sm table-bordered">

    {{-- <tr><th>Stage</th><td>{{ $prospect->temperature->tempName ?? '-' }}</td></tr> --}}
    <tr>
    <th>Stage and Number</th>
    <td>
        {{-- @php dd($prospect->temperature) @endphp --}}
        @if($prospect->temperature->tempCodeName ==1 || $prospect->temperature->tempCodeName == 7)
            <span class="badge badge-warning">LEAD</span>
            <span class="ml-2 text-muted">Update to Promo / Prospect</span>

        @elseif($prospect->temperature->tempCodeName == 2)
            <span class="badge badge-success">PROSPECT</span>
            <span class="ml-2">{{ $prospect->prospect_no ?? '-' }}</span>

        @elseif($prospect->temperature->tempCodeName == 6)
            <span class="badge badge-info">PROMO</span>
            <span class="ml-2">{{ $prospect->promo_no ?? ($prospect->prospect_no ?? '-') }}</span>

        @endif
    </td>
    </tr>
    <tr><th>Hospital</th><td>{{ $prospect->hospital->name ?? '-' }}</td></tr>
    <tr><th>Department</th><td>{{ $prospect->department->name ?? '-' }}</td></tr>
    <tr><th>Business Unit</th><td>{{ $prospect->unit->name ?? '-' }}</td></tr>
    <tr><th>Product</th><td>{{ $prospect->config->name ?? $prospect->config->model_type ?? '-' }}</td></tr>
    <tr><th>Chance</th><td>{{ $prospect->review->chance ?? '-' }}</td></tr>
    <tr><th>Next Action</th><td>{{ $prospect->review->next_action ?? '-' }}</td></tr>
  </table>
@endif

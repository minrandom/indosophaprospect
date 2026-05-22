@include('tabs.task_detail._task_header')

@if(!$reference)
    <div class="alert alert-warning">Installbase reference not found.</div>
@else
    <table class="table table-sm table-bordered">
        <tr><th style="width:35%;">IB Code</th><td>{{ $reference->installbase_code ?? '-' }}</td></tr>
        <tr><th>Hospital</th><td>{{ $reference->hospital->name ?? '-' }}</td></tr>
        <tr><th>Province</th><td>{{ $reference->hospital->province->name ?? '-' }}</td></tr>
        <tr><th>Brand</th><td>{{ $reference->product->brand->name ?? '-' }}</td></tr>
        <tr><th>Category</th><td>{{ $reference->product->category->name ?? '-' }}</td></tr>
        <tr><th>Model / Type</th><td>{{ $reference->product->model_type ?? '-' }}</td></tr>
        <tr><th>Serial Number</th><td>{{ $reference->serial_number ?? '-' }}</td></tr>
        <tr><th>Installation Date</th><td>{{ $reference->installation_date ? \Carbon\Carbon::parse($reference->installation_date)->format('d-M-y') : '-' }}</td></tr>
        <tr><th>Status</th><td>{{ $reference->installbase_status ?? '-' }}</td></tr>
        <tr><th>End Warranty</th><td>{{ $reference->end_of_warranty ? \Carbon\Carbon::parse($reference->end_of_warranty)->format('d-M-y') : '-' }}</td></tr>
    </table>
@endif

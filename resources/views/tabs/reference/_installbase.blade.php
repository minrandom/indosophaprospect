@if(!$installbase)
  <div class="alert alert-warning">Installbase reference not found.</div>
@else
  <table class="table table-sm table-bordered">
    <tr><th>IB Code</th><td>{{ $installbase->installbase_code ?? '-' }}</td></tr>
    <tr><th>Hospital</th><td>{{ $installbase->hospital->name ?? '-' }}</td></tr>
    <tr><th>Province</th><td>{{ $installbase->hospital->province->name ?? '-' }}</td></tr>
    <tr><th>Product</th><td>{{ $installbase->product->name ?? $installbase->product->model_type ?? '-' }}</td></tr>
    <tr><th>Serial Number</th><td>{{ $installbase->serial_number ?? '-' }}</td></tr>
    <tr><th>Status</th><td>{{ $installbase->installbase_status ?? '-' }}</td></tr>
  </table>
@endif

@extends('layout.backend.app', ['title' => 'Installbase Task'])

@section('content')
<div class="container-fluid">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card shadow border-0 mb-4" style="border-radius:1rem;">
    <div class="card-body">
      <h4 class="mb-3">Installbase Task Update</h4>

      <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
      <div class="mb-2"><b>Task Ref:</b> {{ $task->task_reference }}</div>
      <div class="mb-2"><b>Code Ref:</b> {{ $task->code_ref }}</div>
    </div>
  </div>

  <form method="POST" action="{{ route('missions.task.installbase.update', $task->id) }}">
    @csrf

    <div class="card shadow border-0" style="border-radius:1rem;">
      <div class="card-body">

        <div class="row">
          <div class="col-md-6 mb-2">
            <div class="small text-muted">IB Code</div>
            <div class="font-weight-bold">{{ $installbase->installbase_code ?? '-' }}</div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="small text-muted">Province</div>
            <div class="font-weight-bold">{{ optional(optional($installbase->hospital)->province)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">City</div>
            <div class="font-weight-bold">{{ optional($installbase->hospital)->city ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Hospital</div>
            <div class="font-weight-bold">{{ optional($installbase->hospital)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Department</label>
            <input type="text" name="department" class="form-control"
                   value="{{ old('department', $installbase->department) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">PIC to Recall</label>
            <input type="text" name="pic_to_recall" class="form-control"
                   value="{{ old('pic_to_recall', $installbase->pic_to_recall) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Department Phone</label>
            <input type="text" name="department_phone" class="form-control"
                   value="{{ old('department_phone', $installbase->department_phone) }}">
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Brand</div>
            <div class="font-weight-bold">{{ optional(optional($installbase->product)->brand)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Category</div>
            <div class="font-weight-bold">{{ optional(optional($installbase->product)->category)->name ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <div class="small text-muted">Model / Type</div>
            <div class="font-weight-bold">{{ optional($installbase->product)->model_type ?? '-' }}</div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Serial Number</label>
            <input type="text" name="serial_number" class="form-control"
                   value="{{ old('serial_number', $installbase->serial_number) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Installation Date</label>
            <input type="date" name="installation_date" class="form-control"
                   value="{{ old('installation_date', $installbase->installation_date ? \Carbon\Carbon::parse($installbase->installation_date)->format('Y-m-d') : '') }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">Installation Status</label>
            <input type="text" name="installbase_status" class="form-control"
                   value="{{ old('installbase_status', $installbase->installbase_status) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="small text-muted">End Of Warranty</label>
            <input type="date" name="end_of_warranty" class="form-control"
                   value="{{ old('end_of_warranty', $installbase->end_of_warranty ? \Carbon\Carbon::parse($installbase->end_of_warranty)->format('Y-m-d') : '') }}">
          </div>
        </div>

      </div>

      <div class="card-footer bg-white text-right">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Update Installbase</button>
      </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <label class="small text-muted">Report / Notes</label>
            <textarea name="report_result"
                    class="form-control"
                    rows="4"
                    placeholder="Write task result / notes here...">{{ old('report_result', $task->report_result) }}</textarea>
        </div>
    </div>
  </form>

</div>
@endsection

@extends('layout.backend.app', ['title' => 'Mission Report', 'pageTitle' => ''])

@section('content')
<div class="container-fluid">

  <div class="card shadow border-0 mb-4" style="border-radius:1.25rem;">
    <div class="card-body">
      <div class="h5 font-weight-bold mb-1">Task Report</div>
      <div class="text-muted small">
       Task Code: <b>{{ $mission->code }}</b> |
        Ref: <b class="text-uppercase">{{ $mission->task_reference }}</b>
      </div>
    </div>
  </div>

  <form method="POST" action="{{ route('missions.report.submit', $mission->id) }}">
    @csrf

    <div class="card shadow border-0" style="border-radius:1.25rem;">
      <div class="card-body">

        <div class="form-group">
          <label class="small text-uppercase text-muted">Summary (required)</label>
          <input type="text" name="summary" class="form-control" value="{{ old('summary') }}" required>
          @error('summary') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        @if($mission->task_reference === 'installbase')
          @include('modal._report_installbase')
        @elseif($mission->task_reference === 'prospect')
          @include('modal._report_prospect')
        @else
          @include('modal._report_generic')
        @endif

      </div>

      <div class="card-footer bg-white d-flex justify-content-end" style="gap:10px;">
        <a href="{{ route('missions.pool') }}" class="btn btn-secondary">Back</a>
        <button class="btn btn-primary">Submit Report & Done</button>
      </div>
    </div>
  </form>

</div>
@endsection

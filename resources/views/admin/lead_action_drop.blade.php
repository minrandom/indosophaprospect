@extends('layout.backend.app', ['title' => 'Lead Drop'])

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
            <h4 class="mb-3">Lead Action - Drop</h4>

            <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
            <div class="mb-2"><b>Task Ref:</b> {{ $task->task_reference }}</div>
            <div class="mb-2"><b>Code Ref:</b> {{ $task->code_ref }}</div>
            <div class="mb-2"><b>Current Stage:</b> {{ optional($prospect->temperature)->tempName ?? '-' }}</div>
            <div class="mb-2"><b>Hospital:</b> {{ optional($prospect->hospital)->name ?? '-' }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('missions.task.lead.drop.submit', $task->id) }}">
        @csrf

        <div class="card shadow border-0" style="border-radius:1rem;">
            <div class="card-body">

                <div class="form-group">
                    <label class="small text-uppercase text-muted">Why drop this lead?</label>
                    <textarea name="drop_comment" class="form-control" rows="4" required>{{ old('drop_comment') }}</textarea>
                    @error('drop_comment')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="small text-uppercase text-muted">Report Task</label>
                    <textarea name="report_result" class="form-control" rows="4" required>{{ old('report_result') }}</textarea>
                    @error('report_result')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="card-footer bg-white text-right">
                <a href="{{ route('missions.runs.show', $task->mission_run_id) }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-danger">Submit Drop Request</button>
            </div>
        </div>
    </form>

</div>
@endsection

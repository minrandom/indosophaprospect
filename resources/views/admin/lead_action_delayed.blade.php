@extends('layout.backend.app', ['title' => 'Delayed Lead'])

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
            <h4 class="mb-3">Lead Action - Delayed Lead</h4>

            <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
            <div class="mb-2"><b>Task Ref:</b> {{ $task->task_reference }}</div>
            <div class="mb-2"><b>Code Ref:</b> {{ $task->code_ref }}</div>
            <div class="mb-2"><b>Current Stage:</b> {{ optional($prospect->temperature)->tempName ?? '-' }}</div>
            <div class="mb-2"><b>Hospital:</b> {{ optional($prospect->hospital)->name ?? '-' }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('missions.task.lead.delayed.submit', $task->id) }}">
        @csrf

        <div class="card shadow border-0" style="border-radius:1rem;">
            <div class="card-body">

                <div class="form-group">
                    <label class="small text-uppercase text-muted">Why delay this lead?</label>
                    <textarea name="delay_comment" class="form-control" rows="4" required>{{ old('delay_comment') }}</textarea>
                    @error('delay_comment')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="small text-uppercase text-muted">Delay Until</label>
                    <input type="date" name="delay_until" class="form-control" value="{{ old('delay_until') }}" required>
                    @error('delay_until')
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
                <button type="submit" class="btn btn-warning">Submit Delayed Request</button>
            </div>
        </div>
    </form>

</div>
@endsection

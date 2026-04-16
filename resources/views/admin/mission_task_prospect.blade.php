@extends('layout.backend.app', ['title' => 'Prospect Task'])

@section('content')
<div class="container-fluid">
  <div class="card shadow border-0" style="border-radius:1rem;">
    <div class="card-body">
      <h4 class="mb-3">Prospect Task Page</h4>

      <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
      <div class="mb-2"><b>Task Ref:</b> {{ $task->task_reference }}</div>
      <div class="mb-2"><b>Code Ref:</b> {{ $task->code_ref }}</div>

      @if($prospect)
        <hr>
        <div class="mb-2"><b>Prospect ID:</b> {{ $prospect->id }}</div>
        <div class="mb-2"><b>Prospect Name:</b> {{ $prospect->name ?? '-' }}</div>
      @else
        <div class="alert alert-warning mt-3">Prospect data not found.</div>
      @endif
    </div>
  </div>
</div>
@endsection

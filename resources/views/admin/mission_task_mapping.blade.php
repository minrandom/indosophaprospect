@extends('layout.backend.app', ['title' => 'Mapping Task'])

@section('content')
<div class="container-fluid">
  <div class="card shadow border-0" style="border-radius:1rem;">
    <div class="card-body">
      <h4 class="mb-3">Mapping Task Page</h4>

      <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
      <div class="mb-2"><b>Task Ref:</b> {{ $task->task_reference }}</div>
      <div class="mb-2"><b>Code Ref:</b> {{ $task->code_ref }}</div>

      <div class="alert alert-info mt-3">
        Mapping update page placeholder.
      </div>
    </div>
  </div>
</div>
@endsection

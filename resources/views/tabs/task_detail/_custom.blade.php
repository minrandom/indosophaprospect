@include('tabs.task_detail._task_header')

<table class="table table-sm table-bordered">
    <tr><th style="width:35%;">User to Meet</th><td>{{ $task->user_to_meet ?? '-' }}</td></tr>
    <tr><th>Report Result</th><td>{{ $task->report_result ?? '-' }}</td></tr>
</table>

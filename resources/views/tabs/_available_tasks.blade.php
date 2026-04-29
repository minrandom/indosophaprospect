<table class="table table-sm">
@foreach($tasks as $t)
<tr>
    <td>
        <input type="checkbox" class="js-task-checkbox" value="{{ $t->id }}">
    </td>
    <td>{{ $t->code }}</td>
    <td>{{ $t->task_purpose }}</td>
    <td>{{ $t->departmentRelation->name ?? '-' }}</td>
</tr>
@endforeach
</table>

@extends('layout.backend.app',[
  'title' => 'Pending Mission',
  'pageTitle' => '',
])

@section('content')
<div class="container-fluid">

{{-- TOP HEADER --}}
<div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
  <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">
    <div class="row align-items-center">

      {{-- LEFT TITLE --}}
      <div class="col-12 col-lg-4 d-flex align-items-center mb-3 mb-lg-0">
        <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
        <div class="text-white font-weight-bold text-uppercase" style="letter-spacing:1px;">
          Pending Problems:<br>Mission
        </div>
      </div>

      {{-- RIGHT INFO --}}
      <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-lg-end align-items-center flex-wrap">

          <div class="text-white font-weight-bold text-uppercase mr-3"
               style="letter-spacing:1px;">
            <div>{{ $hospital->name }}</div>
            <div class="small text-uppercase">{{ $hospital->target ?? '-' }}</div>
          </div>

          <div class="pl-3"
               style="border-left:1px solid rgba(255,255,255,.35);">
            <div class="text-white font-weight-bold text-uppercase">
              {{ $hospital->city }}
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

{{-- Counter TOP --}}
<div class="text-center mb-4">
  <h4 class="font-weight-bold text-uppercase">
    YOU HAVE
    <span class="text-danger">
      {{ count($missions) }}
    </span>
    PENDING PROBLEMS
  </h4>
</div>

{{-- data table mission --}}
<div class="card shadow border-0" style="border-radius:1.25rem;">
  <div class="card-body">

    <div class="table-responsive">
      <table class="table table-bordered table-hover" id="missionTable" width="100%">
        <thead class="thead-light text-uppercase">
          <tr>
            <th>Equipment ID</th>
            <th>Issue</th>
            <th>Priority</th>
            <th>Deadline</th>
            <th>Start Task</th>
          </tr>
        </thead>
        <tbody>
          @foreach($missions as $m)
            <tr>
              <td>{{ $m['equipment_id'] }}</td>
              <td>{{ $m['issue'] }}</td>
              <td>
                <span class="badge
                  @if($m['priority']=='High') badge-danger
                  @elseif($m['priority']=='Medium') badge-warning
                  @else badge-secondary
                  @endif">
                  {{ $m['priority'] }}
                </span>
              </td>
              <td>{{ $m['deadline'] }}</td>
              <td class="text-center">
                <a href="#" class="btn btn-sm btn-primary">
                  Start
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection

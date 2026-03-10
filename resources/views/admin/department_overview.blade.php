@extends('layout.backend.app',[
  'title' => 'Department Overview',
  'pageTitle' => '',
])

@section('content')
<div class="container-fluid">

  {{-- TOP HEADER STRIP --}}
  <div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
    <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">
      <div class="row align-items-center">

        {{-- LEFT: TITLE + SEARCH --}}
        <div class="col-12 col-lg-6 mb-3 mb-lg-0">
          <div class="d-flex align-items-center justify-content-between flex-wrap">

            {{-- TITLE --}}
            <div class="d-flex align-items-center mr-3 mb-2 mb-md-0">
              <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
              <div class="text-white font-weight-bold text-uppercase" style="letter-spacing:1px;">
                {{ strtoupper($department->name) }}<br>Overview
              </div>
            </div>

            {{-- SEARCH DEPARTMENT (same hospital) --}}
            <form id="deptSearchForm" method="GET" class="d-flex align-items-center mb-2 mb-md-0">
              <select id="department_id"
                      class="form-control form-control-sm mr-2"
                      name="department_id"
                      required
                      style="min-width:220px;">
                <option value="">Select Department</option>
                @foreach($departments as $d)
                  <option value="{{ $d->id }}" {{ $d->id == $department->id ? 'selected' : '' }}>
                    {{ $d->name }}
                  </option>
                @endforeach
              </select>

              <button type="submit" class="btn btn-light btn-sm font-weight-bold">
                Search
              </button>
            </form>

          </div>
        </div>

        {{-- RIGHT: HOSPITAL INFO --}}
        <div class="col-12 col-lg-6">
          <div class="d-flex justify-content-lg-end align-items-center flex-wrap">

            <div class="text-white font-weight-bold text-uppercase mr-3" style="letter-spacing:1px;">
              <div>{{ $hospital->name }}</div>
              <div class="small text-uppercase">{{ $hospital->target ?? '-' }}</div>
            </div>

            <div class="pl-3" style="border-left:1px solid rgba(255,255,255,.35);">
              <div class="text-white font-weight-bold text-uppercase">
                {{ $hospital->city }}
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- NAV TABS --}}
  <div class="card shadow border-0 mb-4" style="border-radius: 1.25rem;">
    <div class="card-body">

      <ul class="nav nav-tabs" id="deptTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active font-weight-bold" id="hr-tab" data-toggle="tab" href="#hr" role="tab">
            Human Resource
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link font-weight-bold" id="market-tab" data-toggle="tab" href="#market" role="tab">
            Market Information
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link font-weight-bold" id="ib-tab" data-toggle="tab" href="#ib" role="tab">
            IB Summary
          </a>
        </li>
      </ul>

      <div class="tab-content pt-4" id="deptTabContent">

        {{-- TAB 1: HUMAN RESOURCE --}}
        <div class="tab-pane fade show active" id="hr" role="tabpanel">
                @include('tabs._hr_deptoverview')
        </div>

        {{-- TAB 2: MARKET INFORMATION --}}
        <div class="tab-pane fade" id="market" role="tabpanel">
            @include('tabs._market_info')
        </div>

        {{-- TAB 3: IB SUMMARY --}}
        <div class="tab-pane fade" id="ib" role="tabpanel">

            @include('tabs._ib_dept')

        </div>

      </div>{{-- tab-content --}}

    </div>
  </div>

</div>
@endsection

@push('js')

  @include('tabs.tabjs._ib_chart_js')

@endpush

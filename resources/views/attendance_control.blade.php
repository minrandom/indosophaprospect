@extends('layout.backend.app', [
    'title' => 'Attendance Control',
    'pageTitle' => ''
])

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Attendance Control</h1>
    </div>

    <div class="card shadow mb-4 border-0" style="border-radius: 1rem;">
        <div class="card-body">

            {{-- NAV TABS --}}
            <ul class="nav nav-tabs mb-3" id="attendanceTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active"
                       id="old-version-tab"
                       data-toggle="tab"
                       href="#old-version"
                       role="tab"
                       aria-controls="old-version"
                       aria-selected="true">
                        Attendance Old List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                       id="new-version-tab"
                       data-toggle="tab"
                       href="#new-version"
                       role="tab"
                       aria-controls="new-version"
                       aria-selected="false">
                        Attendance New Version
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="attendanceTabContent">

                {{-- OLD VERSION TAB --}}
                <div class="tab-pane fade show active"
                     id="old-version"
                     role="tabpanel"
                     aria-labelledby="old-version-tab">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="thead-light text-uppercase small">
                                <tr>
                                    <th>User</th>
                                    <th>Visit Target</th>
                                    <th>Check-In Location</th>
                                    <th>Check-In Time (GMT+7)</th>
                                    <th>Check-In Photo</th>
                                    <th>Check-Out Location</th>
                                    <th>Check-Out Time</th>
                                    <th>Check-Out Photo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($oldRows as $row)
                                    <tr>
                                        <td>{{ $row['user_name'] }}</td>
                                        <td>{{ $row['visit_target'] }}</td>
                                        <td>{{ $row['check_in_location'] }}</td>
                                        <td>{{ $row['check_in_time'] }}</td>
                                        <td class="text-center">
                                            <img src="{{ $row['check_in_photo'] }}"
                                                 alt="Check In Photo"
                                                 style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                        </td>

                                        <td>{{ $row['check_out_location'] }}</td>
                                        <td>{{ $row['check_out_time'] }}</td>
                                        <td class="text-center">
                                            <img src="{{ $row['check_out_photo'] }}"
                                                 alt="Check Out Photo"
                                                 style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No old attendance data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- NEW VERSION TAB --}}
                <div class="tab-pane fade"
                     id="new-version"
                     role="tabpanel"
                     aria-labelledby="new-version-tab">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="thead-light text-uppercase small">
                                <tr>
                                    <th>User</th>
                                    <th>Visit ID</th>
                                    <th>Visit Schedule</th>
                                    <th>Hospital Name</th>
                                    <th>Check-In Location</th>
                                    <th>Check-In Time (GMT+7)</th>
                                    <th>Check-In Photo</th>
                                    <th>Check-Out Location</th>
                                    <th>Check-Out Time</th>
                                    <th>Check-Out Photo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($newRows as $row)
                                    <tr>
                                        <td>{{ $row['user_name'] }}</td>

                                        <td>{{ $row['visit_id'] }}</td>
                                        <td>{{ $row['visit_schedule'] }}</td>
                                        <td>{{ $row['hospital_name'] }}</td>
                                        <td>{{ $row['check_in_location'] }}</td>
                                        <td>{{ $row['check_in_time'] }}</td>
                                        <td class="text-center">
                                            <img src="{{ $row['check_in_photo'] }}"
                                                 alt="Check In Photo"
                                                 style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                        </td>
                                        <td>{{ $row['check_out_location'] }}</td>
                                        <td>{{ $row['check_out_time'] }}</td>
                                        <td class="text-center">
                                            <img src="{{ $row['check_out_photo'] }}"
                                                 alt="Check Out Photo"
                                                 style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No new attendance data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection

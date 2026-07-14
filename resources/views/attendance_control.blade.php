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
                {{-- <li class="nav-item">
                    <a class="nav-link"
                       id="new-version-tab"
                       data-toggle="tab"
                       href="#new-version"
                       role="tab"
                       aria-controls="new-version"
                       aria-selected="false">
                        Attendance New Version
                    </a>
                </li> --}}
            </ul>

            <div class="tab-content" id="attendanceTabContent">

                {{-- OLD VERSION TAB --}}
                <div class="tab-pane fade show active"
                     id="old-version"
                     role="tabpanel"
                     aria-labelledby="old-version-tab">

                <form method="GET"
                        action="{{ url()->current() }}"
                        class="mb-3">

                        <div class="row align-items-end">

                            <div class="col-md-6 mb-2">
                                <label class="small text-uppercase text-muted">
                                    Search
                                </label>

                                <input type="text"
                                    name="keyword"
                                    class="form-control"
                                    value="{{ request('keyword') }}"
                                    placeholder="Search user name, hospital, or visit code...">
                            </div>

                            <div class="col-md-2 mb-2">
                                <label class="small text-uppercase text-muted">
                                    Rows
                                </label>

                                <select name="per_page" class="form-control">
                                    @foreach([10, 20, 50, 100] as $size)
                                        <option value="{{ $size }}"
                                            {{ (int)request('per_page', 20) === $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-2">
                                <button type="submit"
                                        class="btn btn-primary">
                                    <i class="fas fa-search mr-1"></i>
                                    Filter
                                </button>

                                <a href="{{ url()->current() }}"
                                class="btn btn-light">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>

                    <div class="d-flex justify-content-between mt-3">

                        @if($oldRows->onFirstPage())
                            <button class="btn btn-secondary" disabled>
                                ← Previous
                            </button>
                        @else
                            <a href="{{ $oldRows->previousPageUrl() }}"
                            class="btn btn-primary">
                                ← Previous
                            </a>
                        @endif

                        @if($oldRows->hasMorePages())
                            <a href="{{ $oldRows->nextPageUrl() }}"
                            class="btn btn-primary">
                                Next →
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                Next →
                            </button>
                        @endif

                    </div>
                    <div>   </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="thead-light text-uppercase small">
                                <tr>
                                    <th>User</th>
                                    <th>Check-In Location</th>
                                    <th>Check-In Time</th>
                                    <th>Check-In Photo</th>
                                    <th>Check-Out Location</th>
                                    <th>Check-Out Time</th>
                                    <th>Check-Out Photo</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($oldRows as $row)
                                    <tr>
                                        <td class="align-middle">
                                            {{ $row['user_name'] }}
                                        </td>

                                        <td class="align-middle">
                                            {{ $row['check_in_location'] }}
                                        </td>

                                        <td class="align-middle">
                                            {{ $row['check_in_time'] }}
                                        </td>

                                        <td class="text-center align-middle">
                                            <img src="{{ $row['check_in_photo'] }}"
                                                alt="Check-In Photo"
                                                loading="lazy"
                                                class="js-attendance-photo"
                                                data-full-photo="{{ $row['check_in_photo'] }}"
                                                onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';"
                                                style="
                                                    width:70px;
                                                    height:70px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                    cursor:pointer;
                                                ">
                                        </td>

                                        <td class="align-middle">
                                            @if($row['has_checkout'])
                                                {{ $row['check_out_location'] }}
                                            @else
                                                <span class="badge badge-warning">
                                                    Not Checkout
                                                </span>
                                            @endif
                                        </td>

                                        <td class="align-middle">
                                            {{ $row['check_out_time'] }}
                                        </td>

                                        <td class="text-center align-middle">
                                            <img src="{{ $row['check_out_photo'] }}"
                                                alt="Check-Out Photo"
                                                loading="lazy"
                                                class="js-attendance-photo"
                                                data-full-photo="{{ $row['check_out_photo'] }}"
                                                onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';"
                                                style="
                                                    width:70px;
                                                    height:70px;
                                                    object-fit:cover;
                                                    border-radius:8px;
                                                    cursor:pointer;
                                                ">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="text-center text-muted py-4">
                                            No attendance data found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-3">

                        @if($oldRows->onFirstPage())
                            <button class="btn btn-secondary" disabled>
                                ← Previous
                            </button>
                        @else
                            <a href="{{ $oldRows->previousPageUrl() }}"
                            class="btn btn-primary">
                                ← Previous
                            </a>
                        @endif

                        @if($oldRows->hasMorePages())
                            <a href="{{ $oldRows->nextPageUrl() }}"
                            class="btn btn-primary">
                                Next →
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                Next →
                            </button>
                        @endif

                    </div>
                </div>

                {{-- NEW VERSION TAB
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
                </div> --}}

            </div>

        </div>
    </div>

    <div class="modal fade"
            id="attendancePhotoModal"
            tabindex="-1"
            role="dialog">

            <div class="modal-dialog modal-lg modal-dialog-centered"
                role="document">

                <div class="modal-content"
                    style="border-radius:1rem;">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Attendance Photo
                        </h5>

                        <button type="button"
                                class="close"
                                data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <img id="attendancePhotoPreview"
                            src=""
                            alt="Attendance Photo"
                            style="
                                max-width:100%;
                                max-height:75vh;
                                border-radius:10px;
                            ">
                    </div>

                </div>
            </div>
        </div>

</div>

<script>
$(function () {
    $(document).on('click', '.js-attendance-photo', function () {
        const photoUrl = $(this).data('full-photo');

        $('#attendancePhotoPreview').attr('src', photoUrl);
        $('#attendancePhotoModal').modal('show');
    });
});
</script>
@endsection

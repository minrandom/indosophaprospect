@extends('layout.backend.app',[
    'title' => 'test scheduling',
    'pageTitle' =>'test scheduling',
])

@push('css')


<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>





@endpush

@section('content')
<div class="container">
    <h2>Calendar</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#activityModal">+ Add Activity</button>
    <div id="calendar"></div>
</div>


<!-- Modal for Activity Details -->
<div class="modal fade" id="activityDetailModal" tabindex="-1" role="dialog" aria-labelledby="activityDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="activityDetailModalLabel">Activity Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Filled dynamically -->
      </div>
    </div>
  </div>
</div>


@include('modal._activity_modal')

@stop

@push('js')



<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
 <script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>





<script>
$(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month'
                },
                defaultView: 'month',
                events: @json($activities->map(function($a) {
                    return [
                        'title' => $a->hospital->name . '||' . $a->purpose, // separator
                        'start' => $a->start,
                        'end' => $a->end

                    ];
                })),
                eventRender : function(event, element)
                {
                    var parts = event.title.split('||');
                    var hospital = parts[0] || '';
                    var purpose = parts[1] || '';

                    var html = `
                        <div style="font-weight: bold; font-size: 16px;">${hospital}</div>
                        <div style="font-size: 12px; color: blue;">${purpose}</div>
                    `;
                    element.find('.fc-time').remove();
                     element.find('.fc-title').html(html);
                },
                eventClick: function(event, jsEvent, view) {
                    let [hospitalName, purpose] = event.title.split('||');

    // Optional: fallback if title is malformed
                  hospitalName = hospitalName?.trim() || 'Unknown';
                purpose = purpose?.trim() || '-';
                    // Fill modal fields
                    $('#activityDetailModal .modal-title').text(hospitalName);
                    $('#activityDetailModal .modal-body').html(`
                        <p><strong>Purpose:</strong> ${purpose}</p>
                        <p><strong>Start:</strong> ${event.start.format('YYYY-MM-DD HH:mm')}</p>
                        <p><strong>End:</strong> ${event.end ? event.end.format('YYYY-MM-DD HH:mm') : '-'}</p>
                    `);
                    $('#activityDetailModal').modal('show');
                }


            });

            $('#activity-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: '{{ route("activities.store") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#activityModal').modal('hide');
                        location.reload(); // re-fetch events
                    },
                    error: function(err) {
                        alert('Error saving activity');
                    }
                });
            });
        });


$('#province').on('change', function () {
    var provinceId = $(this).val();
    $('#hospital').empty().append('<option value="">Loading...</option>');

    if (provinceId) {
        $.ajax({
            url: '/admin/get-hospitals/' + provinceId,
            type: 'GET',
            success: function (data) {
                $('#hospital').empty().append('<option value="">-- Select Hospital --</option>');
                $.each(data, function (index, hospital) {
                    $('#hospital').append('<option value="' + hospital.id + '">' + hospital.name + '</option>');
                });
            },
            error: function () {
                alert('Failed to fetch hospitals.');
                $('#hospital').empty().append('<option value="">-- Select Hospital --</option>');
            }
        });
    } else {
        $('#hospital').empty().append('<option value="">-- Select Hospital --</option>');
    }
});


</script>



@endpush


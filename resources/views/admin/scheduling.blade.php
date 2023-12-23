@extends('layout.backend.app',[
    'title' => 'test scheduling',
    'pageTitle' =>'test scheduling',
])

@push('css')
 

<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <!--
<script src="
https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/js/evo-calendar.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/css/evo-calendar.min.css
" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/css/evo-calendar.royal-navy.css"/>
-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>





@endpush

@section('content')
<div class="notify"></div>

<div class="row">
    <div class="col-lg-8">
      <div class="card mb-6">
         <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Calendar Test</h6>
          </div>
          <div class="card-body col-sm-12">
            <div id="calendar"></div>
           
          </div>
      </div>
    
    </div>

    <div class="col-lg-4">
      <div class="card mb-6">
         <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Calendar Test</h6>
          </div>
          <div class="card-body col-sm-4">
            
            <div id="data">test masuk data</div>
          </div>
      </div>
    </div>
  </div>
   

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">View/Update Event</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="text" id="eventId">
              <div class="mb-3">
                  <label for="title" class="form-label">Event Title</label>
                  <input type="text" class="form-control" id="title" placeholder="Event Title">
                  <span id="titleError" class="text-danger"></span>
              </div>
              <div class="mb-3">
                  <label for="province" class="form-label">Province</label>
                  <select class="form-select" id="province">
                      <option value="1">Province 1</option>
                      <option value="2">Province 2</option>
                      <!-- Add more options as needed -->
                  </select>
              </div>
              <div class="mb-3">
                  <label for="rs" class="form-label">RS</label>
                  <select class="form-select" id="rs">
                      <option value="1">RS 1</option>
                      <option value="2">RS 2</option>
                      <!-- Add more options as needed -->
                  </select>
              </div>
              <div class="mb-3">
                  <label for="department" class="form-label">Department</label>
                  <select class="form-select" id="department">
                      <option value="1">Department 1</option>
                      <option value="2">Department 2</option>
                      <!-- Add more options as needed -->
                  </select>
              </div>
              <div class="mb-3">
                  <label for="task" class="form-label">Task</label>
                  <input type="text" class="form-control" id="task" placeholder="Task">
              </div>
              <div class="mb-3">
                  <label for="startDate" class="form-label">Start Date</label>
                  <input type="datetime-local" class="form-control" id="startDate" placeholder="Start Date">
              </div>
              <div class="mb-3">
                  <label for="endDate" class="form-label">End Date</label>
                  <input type="datetime-local" class="form-control" id="endDate" placeholder="End Date">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" id="updateBtn" class="btn btn-primary">Update Event</button>
          </div>
      </div>
  </div>
</div>
<!-- Existing modal code... -->

<!-- Create Event Form Container -->
<div class="col-md-11 offset-1 mt-5 mb-5">
  <div class="row border p-4 mb-4">
      <div class="col-md-12 text-center mb-4">
          <h4>Create Event</h4>
      </div>
      <div class="col-md-6">
          <form id="createEventForm">
              <div class="mb-3">
                  <label for="createTitle" class="form-label">Event Title</label>
                  <input type="text" class="form-control" id="createTitle" placeholder="Event Title">
                  <span id="createTitleError" class="text-danger"></span>
              </div>
              <div class="mb-3">
                  <label for="createStartDate" class="form-label">Start Date</label>
                  <input type="datetime-local" class="form-control" id="createStartDate" placeholder="Start Date">
              </div>
              <div class="mb-3">
                  <label for="createEndDate" class="form-label">End Date</label>
                  <input type="datetime-local" class="form-control" id="createEndDate" placeholder="End Date">
              </div>
          </div>
          <div class="col-md-6">
              <div class="mb-3">
                  <label for="createProvince" class="form-label">Province</label>
                  <br>
                  <select class="form-select" id="createProvince">
                      <option value="1">Province 1</option>
                      <option value="2">Province 2</option>
                      <!-- Add more options as needed -->
                  </select>
              </div>
              <br>
              <div class="mb-3">
                  <label for="createRs" class="form-label">RS</label>
                  <br>
                  <select class="form-select" id="createRs">
                      <option value="1">RS 1</option>
                      <option value="2">RS 2</option>
                      <!-- Add more options as needed -->
                  </select>
              </div>
              <div class="mb-3">
                  <label for="createDepartment" class="form-label">Department</label>
                  <br>
                  <select class="form-select" id="createDepartment">
                      <option value="1">Department 1</option>
                      <option value="2">Department 2</option>
                      <!-- Add more options as needed -->
                  </select>
              </div>

            </div>
            <div class="col-md-12 text-center mb-4">
                <label for="createTask" class="form-label">Task</label>
                <input type="text" class="form-control" id="createTask" placeholder="Task" style="height: 50px;">
            </div>
            <div class="col-md-12 text-center mb-4">
              <button type="button" id="createBtn" class="btn btn-primary">Create Event</button>
            </div>
          </form>
  </div>
  <!-- Calendar Container -->

</div>
         
 
          
@stop

@push('js')



<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<!-- Add jQuery library (required) 
<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
-->
<!-- Add the evo-calendar.js for.. obviously, functionality! 
<script src="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.2/evo-calendar/js/evo-calendar.min.js"></script>
<script type="text/javascript">

  $(document).ready(function(){
    $('#calendar').evoCalendar({
      'theme':'Royal Navy'
    })


  })

   
  
  


</script>-->




<script>
  $(document).ready(function () {

    //  $.ajax({
      //  url: "{{ route('schedule.index') }}",
    //  dataType: 'json',
   
   

      $('#calendar').fullCalendar({
          header: {
              left: 'prev, next today',
              center: 'title',
              right: 'month, agendaWeek, agendaDay',
          },
          //events: response.events,
          selectable: true,
          selectHelper: true,
          editable: true, // Enable drag and drop
          /*
          eventResize: function (event, delta, revertFunc) {
              handleEventResize(event, delta, revertFunc);
          },
          eventDrop: function (event) {
              handleEventDrop(event);
          },
          eventClick: function (event) {
              openEventModal(event);
          },*/
          // Other calendar options...
       });

      
   // });

  });
      // Function to handle event drop (drag and drop)
  /*
      function handleEventDrop(event) {
  var id = event.id;
  var title = event.title;  // Add this line to get the title of the dropped event
 var rs = event.rs;
 var province = event.province;
 var department = event.department;
 var task = event.task;

  var start_date = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
  var end_date = moment(event.end).format('YYYY-MM-DD HH:mm:ss');

  $.ajax({
      url: "{{ route('events.update', '') }}" + '/' + id,
      type: "PATCH",
      dataType: 'json',
      data: {
          id: id,
          title: title,  // Include the title in the data
          province: province,
      rs: rs,
      department: department,
      task: task,
          start_date: start_date,
          end_date: end_date
      },
      success: function (response) {
        Swal.fire({
                      icon: 'success',
                      title: 'Data Sudah Terupdate',
                      text: 'Terimakasih',
                  });
      },
      error: function (error) {
          console.log(error);

          // Revert the event if there's an error
          $('#calendar').fullCalendar('refetchEvents');

          swal("Error", "Failed to update event", "error");
      },
  });
}


      // Function to open event modal for viewing/updating
      function openEventModal(event) {
          $('#eventId').val(event.id);
          $('#title').val(event.title);
         $('#province').val(event.province);
          $('#rs').val(event.rs);
        $('#department').val(event.department);
         $('#task').val(event.task);
          $('#startDate').val(moment(event.start).format('YYYY-MM-DD HH:mm:ss'));
          $('#endDate').val(moment(event.end).format('YYYY-MM-DD HH:mm:ss'));

          $('#eventModal').modal('toggle');
      }

      // Function to handle modal close and update event
      $('#updateBtn').click(function () {
          var id = $('#eventId').val();
          var title = $('#title').val();
          var province = $('#province').val();
          var rs = $('#rs').val();
          var department = $('#department').val();
          var task = $('#task').val();
          var startDate = moment($('#startDate').val()).format('YYYY-MM-DD HH:mm:ss');
          var endDate = moment($('#endDate').val()).format('YYYY-MM-DD HH:mm:ss');

          var eventData = {
      title: title,
      province: province,
      rs: rs,
      department: department,
      task: task,
      start_date: startDate,
      end_date: endDate
  };
          $.ajax({
              url: "{{ route('events.update', '') }}" + '/' + id,
              type: "PATCH",
              dataType: 'json',
              data: eventData,
              success: function (response) {
                  $('#eventModal').modal('hide');
                  Swal.fire({
                      icon: 'success',
                      title: 'Data Sudah Terupdate',
                      text: 'Terimakasih',
                  });
              },
              error: function (error) {
                  if (error.responseJSON.errors) {
                      $('#titleError').html(error.responseJSON.errors.title);
                  }
              },
          });
      });
      $('#createBtn').click(function () {
          var createTitle = $('#createTitle').val();
          var createProvince = $('#createProvince').val();
          var createRs = $('#createRs').val();
          var createDepartment = $('#createDepartment').val();
          var createTask = $('#createTask').val();
          var createStartDate = moment($('#createStartDate').val()).format('YYYY-MM-DD HH:mm:ss');
          var createEndDate = moment($('#createEndDate').val()).format('YYYY-MM-DD HH:mm:ss');

          var createEventData = {
              title: createTitle,
              province: createProvince,
              rs: createRs,
              department: createDepartment,
              task: createTask,
              start_date: createStartDate,
              end_date: createEndDate
          };

          $.ajax({
              url: "{{ route('events.store') }}",
              type: "POST",
              dataType: 'json',
              data: createEventData,
              success: function (response) {
                  // Handle success (if needed)
                  console.log(response);
                  $('#calendar').fullCalendar('refetchEvents');
                  $('#createEventForm')[0].reset();

                  Swal.fire({
                      icon: 'success',
                      title: 'Event Created Successfully',
                      text: 'Terimakasih',
                  }).then((result) => {
    // Check if the user clicked "OK"
    if (result.isConfirmed || result.isDismissed) {
        // Reload the entire page
        location.reload();
    }
});


              },
              error: function (error) {
                  // Handle error (if needed)
                  console.log(error);
              },
          });
      });

  });*/
</script>



@endpush


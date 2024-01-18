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
<!--<button onclick="showSwal()">Show Swal</button>-->

<div class="row">
    <div class="col-lg-8">
      <div class="card mb-6">
        <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Create Schedule and Filter</h6>
          </div>

        
                <div class="card-body col-sm-10">
                   
                    <a class="btn btn-info" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Input Schedule Button</a>
                    <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Calendar Filter</button>                
                </div>
            
                
                <div class="col-sm-12">
                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                        <div class="card card-body">
                            <form id="createEventForm">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="createTask" class="form-label">Task</label>
                                            <input type="text" class="form-control" id="createTask" placeholder="Task" style="height: 50px;">
                                        </div>
                                        <!--
                            <div class="mb-3">
                                <label for="createTitle" class="form-label">Event Title</label>
                                <input type="text" class="form-control" id="createTitle" placeholder="Event Title">
                                <span id="createTitleError" class="text-danger"></span>
                            </div>-->
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
                                <label for="createDepartment" class="form-label">Department</label>
                                <br>
                                <select class="form-select" id="createDepartment">
                                    <option value="1">Department 1</option>
                                    <option value="2">Department 2</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="createRs" class="form-label">RS</label>
                                <br>
                                <select class="form-select" id="createRs">
                                    <option value="1">RS 1</option>
                                    <option value="2">RS 2</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <button type="button" id="createBtn" class="btn btn-primary">Create Event</button>
                        </div>
                    </div>

                </form>
                </div>
            </div>
        
        </div>

        <div class="col-sm-12">
            <div class="collapse multi-collapse" id="multiCollapseExample2">
                <div class="card card-body">
                            <div class="form-group">
                                <label for="userFilter">Filter by User:</label>
                                <select class="form-control" id="userFilter">
                                    <option value="">All Users</option>
                                    
                                </select>
                            </div> 

                </div>
            </div>
        </div>
          
      
    
        
    </div>
    </div>

    
</div> 

</br>

<div class="row">
    <!-- Calendar Column -->
    <div class="col-lg-8">
        <div class="card mb-6">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Schedule Calendar</h6>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Task List Column -->
    <div class="col-lg-4">
        <div class="card mb-6">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Task List</h6>
            </div>
            <div class="card-body">

                <!-- Bootstrap Alert Inside Jumbotron 
                <div class="jumbotron" id="taskListJumbotron">
                    <h1 class="display-4">Task List</h1>-->

                    <!-- Bootstrap Alert -->
                    <ul class="list-group" id="taskList">
                        <!-- List of calendar events will be dynamically inserted here -->
                    </ul>
                    <!-- End Bootstrap Alert -->

               
                <!-- End Bootstrap Alert Inside Jumbotron -->
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

  
  <!-- Calendar Container -->


         
 
          
@stop

@push('js')



<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
 <script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>

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
    var calendar = $('#calendar');
    var userFilter = $('#userFilter');
    $.ajax({
        url: "{{ route('schedule.index') }}",
        method: "GET",
        success: function (response) {
           var eventsdata = response.schedule;
           var userNames = response.userdata
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev, next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView:'agendaWeek',
                events: eventsdata, // Assuming that the response contains the events directly
                selectable: true,
                eventOverlap: true,
                slotEventOverlap: true,
                weekends:false,
                //eventLimit: true, // More events indicator (if you have many events in a day)
   
                selectHelper: true,
                editable: true, // Enable drag and drop
                eventAfterAllRender: function (view) {
                    // Check if the current view is month
                    if (view.name === 'month') {
                        // Hide the time indicator in month view
                        $('.fc-time').hide();
                    } else {
                        // Show the time indicator in other views
                        $('.fc-time').show();
                    }
                },
                eventRender: function (event, element) {
                    var durationInMinutes = moment(event.end).diff(moment(event.start), 'minutes');
        
        // Set a fixed height based on the duration
        var fixedHeight = durationInMinutes * 2; // Adjust the multiplier as needed
        element.css('height', fixedHeight + 'px');
                    // Customize the event HTML here
                    // element.find('.fc-time').hide(),
                    element.find('.fc-title').append('<br>' + event.hospital + ' | ' + event.department);
                },
                eventClick: function (event) {
                    openEventModal(event);
                },

                


                // Other calendar options...
            });

            $('#taskList').empty();
                    // Filter and update tasks based on the current month
                eventsdata.forEach(function (event) {   
                    if(event.status<2){
                        var StartDate = moment(event.start).format('DD/MMM/YY HH:mm');
                     $('#taskList').append('<li class="list-group-item d-flex justify-content-between align-items-center">' +event.hospitalName+"-"+event.departmentName+'</br> '+event.title+'</br>'+StartDate + ' <span class="badge badge-primary badge-pill">14</span></li>');
                    }
                        
                });
            userFilter.select2({
                placeholder: 'All Users',
                width: '40%',
            });
            userFilter.empty();
            userFilter.append('<option value="">All Users</option>');
            $.each(userNames, function (userId, userName) {
                userFilter.append('<option value="' + userId + '">' + userName + '</option>');
            });

           
        },
        error: function (error) {
            console.error("Error fetching events:", error);
        }
    });

    userFilter.on('change', function () {
        var selectedUserId = $(this).val();
    
        // Refetch events based on the selected user ID
        $.ajax({
            url: "{{ route('schedule.index') }}",
            method: "GET",
            data: { user_id: selectedUserId }, // Pass the selected user ID as a parameter
            success: function (response) {
                console.log("Response from server:", response);
                var eventfilter = response.filterschedule;
                console.log("Filtered events:", eventfilter);

                $('#taskList').empty();


                // Update FullCalendar with filtered events
                calendar.fullCalendar('removeEvents');
                calendar.fullCalendar('addEventSource', eventfilter);
                // Refetch events to display the newly added events
                calendar.fullCalendar('refetchEvents');
                
                eventfilter.forEach(function (event) {
                    if(event.status<2){
                        var StartDate = moment(event.start).format('DD/MMM/YY HH:mm');
                     $('#taskList').append('<li class="list-group-item d-flex justify-content-between align-items-center">' +event.hospitalName+"-"+event.departmentName+'</br> '+event.title+'</br>'+StartDate + ' <span class="badge badge-primary badge-pill">14</span></li>');
                    }
            });

                //calendar.fullCalendar('refetchEvents');
            },
            error: function (error) {
                console.error("Error fetching filtered events:", error);
            }
        });
    });



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

*/
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
      };

      // Function to handle modal close and update event
      $('#updateBtn').click(function () {
          var id = $('#eventId').val();
          var task = $('#title').val();
          var province = $('#province').val();
          var rs = $('#rs').val();
          var department = $('#department').val();
          var task = $('#task').val();
          var startDate = moment($('#startDate').val()).format('YYYY-MM-DD HH:mm:ss');
          var endDate = moment($('#endDate').val()).format('YYYY-MM-DD HH:mm:ss');

          var eventData = {
      task: task,
      province: province,
      hospital_id: rs,
      department_id: department,
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
          var createTitle = 'Judul-Default';
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
              start_time: createStartDate,
              end_time: createEndDate
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

      function showSwal() {
            Swal.fire({
                icon: 'success',
                title: 'Test Swal',
                text: 'This is a test Swal notification.',
            });
        }

</script>



@endpush


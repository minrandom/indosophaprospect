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
    <!-- Task List Column -->
    <div class="col-lg-4">
        <div class="card mb-6">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">To Do List</h6>
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
                                        <label for="createPersonInCharge" class="form-label">PIC Task</label>
                                        <br>
                                        <select class="form-select" id="createPersonInCharge">
                                    
                                        </select>
                                     </div>
                                        <div class="mb-3">
                                            <label for="createTask" class="form-label">Task</label>
                                            <input type="text" class="form-control" id="createTask" placeholder="Task" style="height: 38px;" width="40%">
                                        </div>
                                        <!--
                            <div class="mb-3">
                                <label for="createTitle" class="form-label">Event Title</label>
                                <input type="text" class="form-control" id="createTitle" placeholder="Event Title">
                                <span id="createTitleError" class="text-danger"></span>
                            </div>-->
                            <div class="mb-3">
                                <label for="createStartDate" class="form-label">Start Date</label>
                                <input type="datetime-local" class="form-control" id="createStartDate" placeholder="Start Date" style="height: 38px;" width="40%">
                            </div>
                            <div class="mb-3">
                                <label for="createEndDate" class="form-label">End Date</label>
                                <input type="datetime-local" class="form-control" id="createEndDate" placeholder="End Date" style="height: 38px;" width="40%">
                            </div>
                        </div>

                        <div class="col-md-6">
                        
                            <div class="mb-3">
                                <label for="createProvince" class="form-label">Province</label>
                                <br>
                                <select class="form-select" id="createProvince">
                                </select>
                            </div>
                          
                           
                            <div class="mb-3">
                                <label for="createRs" class="form-label">RS</label>
                                <br>
                                <select class="form-select" id="createRs">
                                
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="createDepartment" class="form-label">Department</label>
                                <br>
                                <select class="form-select" id="createDepartment">
                                   
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
    <div class="col-lg-12">
        <div class="card mb-6">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Schedule Calendar</h6>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

   
</div>
   

<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View/Update Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <label for="taskupdate" class="form-label col-md-3">Task</label>
          <div class="col-md-9">
            <input type="text" class="form-control" id="taskupdate" placeholder="Task">
            <span id="titleError" class="text-danger"></span>
          </div>
        </div>
        <div class="row mb-3">
          <label for="personInChargeupdate" class="form-label col-md-3">PIC</label>
          <div class="col-md-9">
            <select class="form-select" id="personInChargeupdate"></select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="province" class="form-label col-md-3">Province</label>
          <div class="col-md-9">
            <select class="form-select" id="province"></select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="rs" class="form-label col-md-3">RS</label>
          <div class="col-md-9">
            <select class="form-select" id="rs"></select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="department" class="form-label col-md-3">Department</label>
          <div class="col-md-9">
            <select class="form-select" id="department"></select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="startDate" class="form-label col-md-3">Start Date</label>
          <div class="col-md-9">
            <input type="datetime-local" class="form-control" id="startDate">
          </div>
        </div>
        <div class="row mb-3">
          <label for="endDate" class="form-label col-md-3">End Date</label>
          <div class="col-md-9">
            <input type="datetime-local" class="form-control" id="endDate">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
     //var createTitle = 'Judul-Default';
          var createProvince = $('#createProvince');
          var createPersonInCharge = $('#createPersonInCharge');
          var createRs = $('#createRs');
          var createDepartment = $('#createDepartment');
          var createTask = $('#createTask');
          var createStartDate =$('#createStartDate');
          var createEndDate =$('#createEndDate');

          var globalEventsData; // Global variable to store events data
var globalFormData; // Global variable to store form data

    function fetchHospitals2(provinceId,selecthospital) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
                    method: "GET",
                    success: function (response) {
                        //console.log(response.hosopt);
                        response.hosopt.forEach(function(hospital) {
                            selecthospital.append('<option value="' + hospital.id + '">' + hospital.name + '</option>');
                        });
                    }
                  });
            };




      
                    $.ajax({
                        url: "{{ route('schedule.index') }}",
                        method: "GET",
                        success: function (response) {
                            var eventsdata = response.schedule;
                            var formdata = response.data;
                            initializeCalendar(eventsdata, formdata);
                            consumeEventData(); // Call the function to consume events data
                        },
                        error: function (error) {
                            console.error("Error fetching events:", error);
                        }
                    });
            
function consumeEventData() {
    // Use the globalEventsData variable wherever you need events data
    console.log(globalEventsData);
    console.log(globalformData);
}     


function initializeCalendar(eventsdata, formdata) {
    globalEventsData = eventsdata;
    globalformData = formdata;
          
           //var userNames = response.data.pic;
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev, next today',
                    center: 'title',
                    right: 'month,listWeek'
                },
                timeFormat: 'HH:mm',
                buttonText: {
                    month:'Monthly Schedule',
                    listWeek: 'Weekly Schedule'
                 },
                defaultView:'listWeek',
                events: globalEventsData, // Assuming that the response contains the events directly
                selectable: true,

                weekends:false,
               // eventLimit: true, // More events indicator (if you have many events in a day)
                
                selectHelper: true,
                //editable: true, // Enable drag and drop
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
                eventRender: function (event, element,view) {
                    element.find('.fc-title').append('<br>' + event.hospitalName + ' | ' + event.create_for_name);
                    element.find('.fc-list-item-title').html("<span style='font-size:1rem'>"+event.hospitalName+ " | "+ event.title +"<span>");
                    element.find('.fc-list-item-title').append('<div style="float: right;font-size:1rem">PIC: ' + event.create_for_name + '</div>');
               
                },
                eventClick: function (event) {
                    openEventModal(event,formdata);
                },

                


                // Other calendar options...
            });
            
            

            userFilter.select2({
                placeholder: 'Pick Users',
                width: '80%' // Adjust the value as needed
            });
            userFilter.empty();
            //userFilter.append('<option value="">All Users</option>');
            formdata.pic.forEach(function(pic) {
                userFilter.append('<option value="' + pic.user_id + '">' + pic.name +'</option>');
            });
            
            
            userFilter.val("");

            createProvince.select2({
                placeholder: 'Select Province',
                width: '80%'
            });

            createProvince.empty();
            createRs.select2({
                placeholder: 'Select Hospital',
                width: '80%'
            });


            createDepartment.empty();
            createDepartment.select2({
                placeholder: 'Select Department',
                width: '80%'
            });

            createProvince.empty();
            formdata.province.forEach(function(province) {
                createProvince.append('<option value="' + province.id + '">' + province.name + '</option>');
            });
            createProvince.val("");

           
               
                createProvince.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  //console.log(selectedProvinceId);
                  fetchHospitals2(selectedProvinceId,createRs);
                });



            createPersonInCharge.select2({
                placeholder: 'Choose User to be PIC',
                width: '80%'
               
            });

            createPersonInCharge.empty();
            formdata.pic.forEach(function(pic) {
                createPersonInCharge.append('<option value="' + pic.user_id + '">' + pic.name + '</option>');
            });
            createPersonInCharge.val("");


            createDepartment.empty();
            formdata.dept.forEach(function(dept){
                createDepartment.append('<option value="' + dept.id + '">' + dept.name + '</option>');
            });
            createDepartment.val("");

           
        }
       


userFilter.on('change', function () {
        var selectedUserId = $(this).val();
  if(selectedUserId>0){
        var filteredEvents = globalEventsData.filter(function(event) {
          return   event.create_for == selectedUserId;
        })
    }
    calendar.fullCalendar('removeEvents');
        calendar.fullCalendar('addEventSource', filteredEvents);
        calendar.fullCalendar('refetchEvents');
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
      function openEventModal(event,data) {
        //console.log(data);

          iddata=$('#id').val(event.id);
          //$('#title').val(event.tit);
         updateprov=$('#province');
         updateprov.empty();
         updateprov.select2({
            width: '80%'}
         );
         data.province.forEach(function(province) {
                updateprov.append('<option value="' + province.id + '">' + province.name + '</option>');
            });

            updateprov.val(event.province);
            updatehospital=$('#rs');
            updatehospital.empty();
           updatehospital.select2({
            width: '80%'}
           );
               
                updateprov.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  //console.log(selectedProvinceId);
                  updatehospital.empty();
                  fetchHospitals2(selectedProvinceId,updatehospital);
                  updatehospital.append('<option value="' + event.hospital + '">' + event.hospitalName + '</option>');
                });

                updatehospital.append('<option value="' + event.hospital + '">' + event.hospitalName + '</option>');
            
            picupdate=$('#personInChargeupdate');
            picupdate.empty();
            picupdate.select2({
            width: '80%'});
            data.pic.forEach(function(pics) {
             picupdate.append('<option value="' + pics.user_id + '">' + pics.name +'</option>');
            });
        picupdate.append('<option value="' + event.create_for + '">' + event.create_for_name + '</option>');
        picupdate.val(event.create_for).prop('selected', true);
           
          
        deptupdate=$('#department');
        deptupdate.select2( {
            width: '80%'});
        deptupdate.empty();
        data.dept.forEach(function(dpt) {
             deptupdate.append('<option value="' + dpt.id + '">' + dpt.name +'</option>');
            });
        deptupdate.append('<option value="' + event.department + '">' + event.departmentName + '</option>');
        deptupdate.val(event.department).prop('selected', true);
         $('#taskupdate').val(event.title);
          $('#startDate').val(moment(event.start).format('YYYY-MM-DD HH:mm:ss'));
          $('#endDate').val(moment(event.end).format('YYYY-MM-DD HH:mm:ss'));

          $('#eventModal').modal('toggle');
      };

      // Function to handle modal close and update event
      $('#updateBtn').click(function () {
        
          var id= $('#id').val();      
          var province = $('#province').val();
          var rs = $('#rs').val();
          var department = $('#department').val();
          var task = $('#taskupdate').val();
          var startDate = moment($('#startDate').val()).format('YYYY-MM-DD HH:mm:ss');
          var endDate = moment($('#endDate').val()).format('YYYY-MM-DD HH:mm:ss');
        var createFor= $('#personInChargeupdate').val();

          var eventData = {
                create_for:createFor,
                province_id: province,
                hospital_id: rs,
                department_id: department,
                task: task,
                start_date: startDate,
                end_date: endDate
            };
            console.log(eventData);
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
                  }).then(function() {// Clear existing events
                    location.reload();
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
       
          var createProvince = $('#createProvince').val();
          var createPersonInCharge = $('#createPersonInCharge').val();
          var createRs = $('#createRs').val();
          var createDepartment = $('#createDepartment').val();
          var createTask = $('#createTask').val();
          var createStartDate = moment($('#createStartDate').val()).format('YYYY-MM-DD HH:mm:ss');
          var createEndDate = moment($('#createEndDate').val()).format('YYYY-MM-DD HH:mm:ss');

          //console.log(createPersonInCharge);
          var createEventData = {
            
              province: createProvince,
              createFor:createPersonInCharge,
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
                  //console.log(response);
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




    });

</script>



@endpush


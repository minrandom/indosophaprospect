@extends('layout.backend.app',[
    'title' => 'Attendance',
    'pageTitle' =>'Event Attendance',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
</a>
        
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                        <th>Waktu Checkin</th> 
                        <th>Lokasi Check in</th>
                            <th>Nama</th>
                           <th>Event</th>
                           <th>Photo Checkin</th>
                           
                       
                           <th>Waktu CheckOut</th>
                            <th>Lokasi Check Out</th>
                           
                            <th>Photo CheckOut</th>
                          
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
</div>

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/regencies.js"></script>

<script type="text/javascript">

  $(function () {
   
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('ListAtt') }}",
        columns: [

            {data: 'jamci' , name: 'checkinTime'},
            {data: 'check_in_loc' , name: 'checkinLoc'},
            {data: 'user.name' , name: 'user_id'},
            {data: 'event.name' , name: 'Event'},
            {
            data: 'photo_data', 
            name: 'photo_in',
            render: function(data, type, full, meta) {
                // Manipulate the URL string
                var newUrl = data.replace(
                    'https://drive.google.com/uc?id=',
                    'https://drive.google.com/file/d/'
                ).replace('&amp;export=media', '/preview');
                
                return '<iframe src="'+newUrl+'" width="80" height="100" allow="autoplay"></iframe>';
              }

            
            },
            {data: 'jamco' , name: 'checkoutTime'},
            {data: 'coloc' , name: 'checkoutLoc'},
            {
            data: 'photo_out', 
            name: 'photo_out',
            render: function(data, type, full, meta) {
                // Manipulate the URL string
                if(data =="Belum Checkout"){
                  return data
                }else {
                var newUrl = data.replace(
                    'https://drive.google.com/uc?id=',
                    'https://drive.google.com/file/d/'
                ).replace('&amp;export=media', '/preview');
                
                return '<iframe src="'+newUrl+'" width="80" height="100" allow="autoplay"></iframe>';
                }
              }

            
            },

        ]
    });
  });

   
    // Create 
    // Create
    // Edit & Update
   

    function flash(type,message){
        $(".notify").html(`<div class="alert alert-`+type+` alert-dismissible fade show" role="alert">
                              `+message+`
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>`)
    }

</script>
@endpush
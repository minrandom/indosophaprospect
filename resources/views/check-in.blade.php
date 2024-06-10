@extends('layout.backend.app',[
    'title' => 'Attendance',
    'pageTitle' =>'Attendance',
    ])
    
    @push('css')
    <link rel="stylesheet" href="{{ asset('openlayers/ol.css') }}">
    <style>
       #map {
            width: 100%;
            height: 400px;
        }
        #video-container {
            position: relative;
        }
        #video {
            display: none;
        }
        #click-photo {
     
            display: none;
        }

    </style>
    <script src="{{ asset('openlayers/dist/ol.js') }}"></script>
    
    @endpush
    @section('content')
    @csrf
    <div class="card">
    <h1 class="h3 mb-4 text-gray-800">Check-in</h1>

    <!-- Check-in Card -->
    <div class="card-body">
    <div id="video-container">
        <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
        <video id="video" width="320" height="240" autoplay playsinline></video>
        
        <button id="click-photo" class="btn btn-primary mt-2"><i class="fas fa-camera"></i>Check IN</button>
    </div>
        <button id="toggle-camera" class="btn btn-primary"><i class='fas fa-street-view'></i> Start Checkin</button>


    </div>

    

    </div>

    @stop
    @push('js')
    <script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>
    <script type="text/javascript">
        
        let toggle_button = document.querySelector("#toggle-camera");
        let video = document.querySelector("#video");
        let click_button = document.querySelector("#click-photo");
        let canvas = document.querySelector("#canvas");
        let image_data_url;

        

    toggle_button.addEventListener('click', function() {
        startCamera()});
       
    click_button.addEventListener('click', function(e) {
            capturePhoto();
    });

    async function startCamera() {
        try {
        $("#sidebarToggle").trigger('click');
        let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                video.srcObject = stream;
                video.style.display = "block";
                click_button.style.display = "block";
                toggle_button.style.display='none';
                
            }catch(err){
                console.error('Error accessing camera:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Camera Error',
                    text: 'Error accessing camera. Please make sure you have granted access.',
                    confirmButtonText: 'OK'
                });
            }
    }


    function capturePhoto() {
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.style.display = "block";
            image_data_url = canvas.toDataURL('image/jpeg');
            stopCamera();
            getLocationAndCheckIn(image_data_url);
    }

    function stopCamera() {
            let stream = video.srcObject;
            let tracks = stream.getTracks();
            tracks.forEach(function(track) {
                track.stop();
            });
            video.srcObject = null;
            video.style.display = "none";
            click_button.style.display = "none";
            toggle_button.style.display = "block";
           
        }

    function getLocationAndCheckIn(photoData) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                showPosition(position, photoData);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Geolocation Error',
                text: 'Geolocation is not supported by this browser.',
                confirmButtonText: 'OK'
            });
        }

        function showPosition(position, photoData) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Perform reverse geocoding using OpenLayers API
            var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latitude + '&lon=' + longitude;
            fetch(url)
            .then(response => response.json())
            .then(data => {
                var placeName = data.display_name.split(',')[0];
                var address = data.address.road + ', ' + data.address.city + ', ' + data.address.country;
                var checkInAt = placeName;
              
                Swal.fire({
                    icon: 'success',
                    title: 'Location Found',
                    html: '<p>Place Name: ' + placeName + '</p><p>Address: ' + address + '</p><img src="' + photoData + '" width="200">',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    didClose: function() {
                        saveCheckInData(placeName, address, checkInAt, photoData);
                    }
                });
            })
            .catch(error => {
                console.log(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Location Error',
                    text: 'Failed to retrieve location information.',
                    confirmButtonText: 'OK'
                });
            });
    }


        function saveCheckInData(placeName, address, checkInAt, photoData) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{route("check-in.store")}}',
                method: 'POST',
                data: {
                    place_name: placeName,
                    address: address,
                    check_in_loc: checkInAt,
                    photo_data: photoData
                },
                 success: function (response) {
            console.log('Data saved successfully:', response);
   
            window.location.reload(true);
            // You can add additional checks or logs here
        },
        error: function (error) {
            console.error('Error saving data:', error);
            // You can add additional error handling here
        }
            });
        }



    
    }
        </script>
@endpush
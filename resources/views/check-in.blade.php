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
        /* Adjust button styles */
#click-photo {
    z-index: 1; /* Ensure button is above video */
}

/* Additional styles for the video container */
#video-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%; /* Ensure the container takes up the full height */
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
        <button id="toggle-camera" class="btn btn-primary">Start Checkin</button>
        <div id="video-container" style="position: relative;">
            <video id="video" width="320" height="240" style="display: none;" autoplay></video>
            <button id="click-photo" class="btn btn-primary mt-2" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;"><i class="fas fa-camera"></i> Click Photo</button>
            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
        </div>

    </div>
    </div>

    @stop
    @push('js')
    <script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>
    <script type="text/javascript">
        
 let toggle_button = document.querySelector("#toggle-camera");
    let video = document.querySelector("#video");
    //let click_button = document.querySelector("#click-photo");
    let canvas = document.querySelector("#canvas");
    let image_data_url;


    toggle_button.addEventListener('click', function() {
        if (toggle_button.innerText === "Start Checkin") {
            startCamera();
        } else {
            capturePhoto();
        }
    });

    function startCamera() {

        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function(stream) {
                video.srcObject = stream;
                video.style.display = "block";
                toggle_button.innerHTML = '<i class="fas fa-camera"></i> Capture Photo';
            })
            .catch(function(err) {
                console.error('Error accessing camera:', err);
                alert('Error accessing camera. Please make sure you have granted access.');
            });
    }


    function capturePhoto() {
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        image_data_url = canvas.toDataURL('image/jpeg');
        getLocationAndCheckIn(image_data_url);
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
            stopCamera();
            // You can add additional checks or logs here
        },
        error: function (error) {
            console.error('Error saving data:', error);
            // You can add additional error handling here
        }
            });
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
        toggle_button.innerText = "Start Checkin";
    }

    
    }
        </script>
@endpush
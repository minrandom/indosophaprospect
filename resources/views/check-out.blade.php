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
    align-items: flex-end;
    position: relative;
}

    </style>
    <script src="{{ asset('openlayers/dist/ol.js') }}"></script>
    @endpush
    @section('content')
    @csrf
    <div class="card">
    <h1 class="h3 mb-4 text-gray-800">Check-OUT</h1>

    <!-- Check-in Card -->
    <div class="card-body">
        <div id="video-container" style="position: relative;">
            <video id="video" width="320" height="240" style="display: none;" autoplay></video>
            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
            <button id="click-photo" class="btn btn-primary mt-2" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: none;"><i class="fas fa-camera"></i> Click Photo</button>
        </div>
        <button id="toggle-camera" class="btn btn-primary"><i class='fas fa-street-view'></i> Checkout</button>


    </div>
    </div>
</br>
</br>
<div class="card" style="width: 18rem;">
  <img src="{{ $hariini->photo_data }}&authuser=0" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Check-in Terakhir </h5>
    <p class="card-text">{{$hariini->created_at}}</p>
    <p class="card-text">{{$hariini->address}}</p>
   <input type='hidden' id='checkinId' value='{{ $hariini->id}}'>
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
        if (toggle_button.innerText === " Checkout") {
            startCamera();
        } else {
            capturePhoto();
        }
    });

    function startCamera() {
        $("#sidebarToggle").trigger('click');
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function(stream) {
                video.srcObject = stream;
                video.style.display = "block";
                toggle_button.innerHTML = '<i class="fas fa-camera"></i> Photo Check-Out';
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
                var checkinid = $("#checkinId").val();
                console.log(checkinid);

                Swal.fire({
                    icon: 'success',
                    title: 'Location Found',
                    html: '<p>Place Name: ' + placeName + '</p><p>Address: ' + address + '</p><img src="' + photoData + '" width="200">',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                    didClose: function() {
                        saveCheckInData(placeName, address, checkInAt, photoData,checkinid);
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


        function saveCheckInData(placeName, address, checkInAt, photoData,checkinid) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{route("check-out.store")}}',
                method: 'POST',
                data: {
                    checkinid:checkinid,
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
        $("#sidebarToggle").trigger('click');
        click_button.style.display = "none";
        toggle_button.innerHTML = "<i class='fas fa-location-check'></i> Start Checkin";
        $("#click-photo").hide();
    }

    
    }
        </script>
@endpush
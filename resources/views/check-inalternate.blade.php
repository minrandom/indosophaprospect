<!DOCTYPE html>
<html lang="en">
<head>
    <title>Check-in</title>
    <link rel="stylesheet" href="{{ asset('openlayers/ol.css') }}">
    <style>
        #map {
            width: 100%;
            height: 400px;
        }
    </style>
    <script src="{{ asset('openlayers/ol.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
 
    @csrf

<button id="testkirimdata">Test Kirim</button>
    <!--<button onclick="getLocation()">Check-in</button>-->
    <button id="start-camera">Start Checkin</button>
<video id="video" width="320" height="240" autoplay></video>
<button id="click-photo">Click Photo</button>
<canvas id="canvas" width="320" height="240"></canvas>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }


        let camera_button = document.querySelector("#start-camera");
        let testkirim = document.querySelector("#testkirimdata");
let video = document.querySelector("#video");
let click_button = document.querySelector("#click-photo");
let canvas = document.querySelector("#canvas");
let image_data_url;
camera_button.addEventListener('click', async function() {
   	let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
	video.srcObject = stream;
});

testkirim.addEventListener('click',function(){

});

click_button.addEventListener('click', function() {
   canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
   	image_data_url = canvas.toDataURL('image/jpeg');
    getLocation();
   	// data url of the image
   	console.log(canvas);
});

        function showPosition(position) {
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
                    var photoData =image_data_url;
                   
                    // Prompt the user to capture a photo
                  
                        // Save the data to the database via AJAX request
                    alert('Place Name: ' + placeName + '\nAddress: ' + address);
                    saveCheckInData(placeName, address, checkInAt, photoData);

                        // Use the place name, address, and photo data as needed
                   

                    // Show the user's location on the map...
                })
                .catch(error => {
                    console.log(error);
                    alert('Failed to retrieve location information.');
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
            // You can add additional checks or logs here
        },
        error: function (error) {
            console.error('Error saving data:', error);
            // You can add additional error handling here
        }
            });
        }
    </script>
</body>
</html>

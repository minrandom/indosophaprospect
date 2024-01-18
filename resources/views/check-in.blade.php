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
    <button onclick="getLocation()">Check-in</button>
    <div id="map"></div>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

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

                    // Prompt the user to capture a photo
                    capturePhoto(function(photoData) {
                        // Save the data to the database via AJAX request
                        saveCheckInData(placeName, address, checkInAt, photoData);

                        // Use the place name, address, and photo data as needed
                        alert('Place Name: ' + placeName + '\nAddress: ' + address);
                    });

                    // Show the user's location on the map...
                })
                .catch(error => {
                    console.log(error);
                    alert('Failed to retrieve location information.');
                });
        }

        function capturePhoto(callback) {
            // Access the device camera and prompt the user to capture a photo
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    var video = document.createElement('video');
                    document.body.appendChild(video);
                    video.srcObject = stream;
                    video.play();

                    // Capture a photo after a delay
                    setTimeout(() => {
                        var canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                        var photoData = canvas.toDataURL('image/jpeg');

                        // Cleanup: stop the video stream and remove the video element
                        video.srcObject.getTracks().forEach(track => track.stop());
                        document.body.removeChild(video);

                        // Pass the captured photo data to the callback function
                        callback(photoData);
                    }, 2000); // Delay for 2 seconds (adjust as needed)
                })
                .catch(error => {
                    console.log(error);
                    alert('Failed to access the camera.');
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
                    console.log(response);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>
</html>

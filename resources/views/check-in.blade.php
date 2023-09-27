<!DOCTYPE html>
<html>
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
                    var checkInAt= placeName;
                    // Save the data to the database via AJAX request
                    saveCheckInData(placeName, address,checkInAt);

                    // Use the place name and address as needed, such as displaying them on the screen
                    alert('Place Name: ' + placeName + '\nAddress: ' + address);

                    // Show the user's location on the map...
                })
                .catch(error => {
                    console.log(error);
                    alert('Failed to retrieve location information.');
                });
        }

        function saveCheckInData(placeName, address ,checkInAt) {

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
                    check_in_loc: checkInAt
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

<!DOCTYPE html>
<html>
<head>
    <title>Get Location</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <button onclick="getLocation()">Get Location</button>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert('Geolocation is not supported by this browser.');
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            
            // Send the location to the server
            $.ajax({
                url: '{{ route("location") }}',
                type: 'POST',
                data: {latitude: latitude, longitude: longitude},
                success: function(response) {
                    alert(response.message);
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while getting the location.');
                }
            });
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert('User denied the request for Geolocation.');
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert('Location information is unavailable.');
                    break;
                case error.TIMEOUT:
                    alert('The request to get user location timed out.');
                    break;
                case error.UNKNOWN_ERROR:
                    alert('An unknown error occurred.');
                    break;
            }
        }
    </script>
</body>
</html>




<!--<!DOCTYPE html>
<html>
<head>
    <title>Get Location</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <button id="getLocationButton">Get Location</button>

    <div id="locationDisplay"></div>

    <script>
        $(document).ready(function() {
            $('#getLocationButton').click(function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    alert('Geolocation is not supported by this browser.');
                }
            });

            function showPosition(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                var locationDisplay = 'Latitude: ' + latitude + '<br>Longitude: ' + longitude;
                $('#locationDisplay').html(locationDisplay);
            }
        });
    </script>
</body>
</html>-->
<!--
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Geolocation App</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Geolocation App</h1>
        <div id="map" style="width: 100%; height: 400px;"></div>
        <button id="getLocationBtn">Get Location</button>
        <div id="result"></div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const getLocationBtn = document.getElementById('getLocationBtn');
            const resultContainer = document.getElementById('result');

            getLocationBtn.addEventListener('click', function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    resultContainer.innerHTML = 'Geolocation is not supported by this browser.';
                }
            });

            function showPosition(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                const accuracy = position.coords.accuracy;

                // Reverse geocoding to get the address
                const geocoder = new google.maps.Geocoder();
                const latlng = { lat: latitude, lng: longitude };

                geocoder.geocode({ location: latlng }, function (results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            resultContainer.innerHTML = `
                                Latitude: ${latitude}<br>
                                Longitude: ${longitude}<br>
                                Accuracy: ${accuracy} meters<br>
                                Address: ${results[0].formatted_address}
                            `;
                        } else {
                            resultContainer.innerHTML = 'No results found.';
                        }
                    } else {
                        resultContainer.innerHTML = 'Geocoder failed due to: ' + status;
                    }
                });
            }
        });
    </script>
</body>
</html>
    -->

    <!--
    <!DOCTYPE html>
<html>
<head>
    <title>Location List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@6.10.1/dist/ol.css" type="text/css">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Location List</h1>

    <div id="map"></div>

    <script src="https://cdn.jsdelivr.net/npm/ol@6.10.1/dist/ol.js"></script>
    <script>
        const locations = {!! json_encode($locations) !!};

        const map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM(),
                }),
            ],
            view: new ol.View({
                center: [0, 0],
                zoom: 2,
            }),
        });

        locations.forEach(location => {
            const coordinate = ol.proj.fromLonLat([location.longitude, location.latitude]);
            const marker = new ol.Feature({
                geometry: new ol.geom.Point(coordinate),
            });

            const iconStyle = new ol.style.Style({
                image: new ol.style.Icon({
                    src: 'https://cdn.rawgit.com/openlayers/ol-icons/master/src/gps_marker.png',
                    scale: 0.05,
                }),
            });

            marker.setStyle(iconStyle);

            const vectorSource = new ol.source.Vector({
                features: [marker],
            });

            const vectorLayer = new ol.layer.Vector({
                source: vectorSource,
            });

            map.addLayer(vectorLayer);
        });
    </script>
</body>
</html> -->


@extends('layout.backend.app', [
    'title' => 'Attendance',
    'pageTitle' => 'Attendance',
])

@push('css')
<link rel="stylesheet" href="{{ asset('openlayers/ol.css') }}">
<style>
    #map-current {
        width: 100%;
        height: 300px;
        margin-bottom: 20px;
    }

    #map-last {
        width: 100%;
        height: 200px; /* Adjust height for the last check-in map */
        margin-top: 10px;
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
    <h1 class="h3 mb-4 text-gray-800">Check-OUT</h1>

    <!-- Top Map: Exact Current Location -->
    <div class="card-body d-flex flex-column align-items-center" >
        <div id="map-current"></div> <!-- Current location map -->

        <div id="video-container" class="mt-4 d-flex flex-column align-items-center">
            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
            <video id="video" width="320" height="240" autoplay playsinline></video>

            <button id="click-photo" class="btn btn-primary mt-2"><i class="fas fa-camera"></i> Check OUT</button>
        </div>
        <button id="toggle-camera" class="btn btn-primary"><i class='fas fa-street-view'></i> Start Check Out</button>
    </div>
</div>
<br />
<br />

<!-- Second Map: Last Check-In Location -->
<div class="card" style="width: 18rem;">
    <img src="{{ $urlphotoshow }}" class="card-img-top" alt="Check-In Photo">
    <div class="card-body">
        <h5 class="card-title">Check-in Terakhir</h5>
        <p class="card-text">{{ $hariini->created_at }}</p>
        <p class="card-text">{{ $hariini->address }}</p>
        <input type='hidden' id='checkinId' value='{{ $hariini->id }}'>
        <div id="map-last"></div> <!-- Last check-in location map -->
    </div>
</div>

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script type="text/javascript">
    let toggle_button = document.querySelector("#toggle-camera");
    let video = document.querySelector("#video");
    let click_button = document.querySelector("#click-photo");
    let canvas = document.querySelector("#canvas");
    let image_data_url;
    let latLng = {}; // Store the user's current location

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize both maps
        initCurrentLocationMap(); // Top map for exact location
        initLastCheckInMap(); // Second map for last check-in location
    });

    toggle_button.addEventListener('click', function () {
        startCamera();
    });

    click_button.addEventListener('click', function () {
        capturePhoto();
    });

    async function startCamera() {
        try {
            $("#sidebarToggle").trigger('click');
            let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            video.srcObject = stream;
            video.style.display = "block";
            click_button.style.display = "block";
            toggle_button.style.display = 'none';
        } catch (err) {
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
        getLocationAndCheckOut(image_data_url);
    }

    function stopCamera() {
        let stream = video.srcObject;
        let tracks = stream.getTracks();
        tracks.forEach(function (track) {
            track.stop();
        });
        video.srcObject = null;
        video.style.display = "none";
        click_button.style.display = "none";
        toggle_button.style.display = "block";
    }

    function initCurrentLocationMap() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                latLng.latitude = position.coords.latitude;
                latLng.longitude = position.coords.longitude;

                // Initialize the map with the user's current location
                var map = new ol.Map({
                    target: 'map-current',
                    layers: [
                        new ol.layer.Tile({
                            source: new ol.source.OSM(),
                        }),
                    ],
                    view: new ol.View({
                        center: ol.proj.fromLonLat([latLng.longitude, latLng.latitude]),
                        zoom: 17,
                    }),
                });

                var marker = new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.fromLonLat([latLng.longitude, latLng.latitude])),
                });

                var markerStyle = new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 1],
                        src: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                        scale: 0.1,
                    }),
                });
                marker.setStyle(markerStyle);

                var vectorSource = new ol.source.Vector({
                    features: [marker],
                });

                var markerVectorLayer = new ol.layer.Vector({
                    source: vectorSource,
                });

                map.addLayer(markerVectorLayer);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Geolocation Error',
                text: 'Geolocation is not supported by this browser.',
                confirmButtonText: 'OK'
            });
        }
    }

    function initLastCheckInMap() {
        var latitude = {{ $hariini->latitude }};
        var longitude = {{ $hariini->longitude }};

        // Initialize the map with the last check-in location
        var map = new ol.Map({
            target: 'map-last',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM(),
                }),
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([longitude, latitude]),
                zoom: 17,
            }),
        });

        var marker = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.fromLonLat([longitude, latitude])),
        });

        var markerStyle = new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 1],
                src: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                scale: 0.1,
            }),
        });
        marker.setStyle(markerStyle);

        var vectorSource = new ol.source.Vector({
            features: [marker],
        });

        var markerVectorLayer = new ol.layer.Vector({
            source: vectorSource,
        });

        map.addLayer(markerVectorLayer);
    }

    function getLocationAndCheckOut(photoData) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
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
            latLng.latitude = position.coords.latitude;
            latLng.longitude = position.coords.longitude;
            var checkinid = $("#checkinId").val();

            var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + latLng.latitude + '&lon=' + latLng.longitude;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var placeName = data.display_name.split(',')[0];
                    var address = data.address.road + ', ' + data.address.city + ', ' + data.address.country;
                    var checkOutAt = placeName;

                    Swal.fire({
                        icon: 'success',
                        title: 'Location Found',
                        html: '<p>Place Name: ' + placeName + '</p><p>Address: ' + address + '</p><img src="' + photoData + '" width="200">',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        didClose: function () {
                            saveCheckOutData(placeName, address, checkOutAt, photoData, checkinid);
                        }
                    });
                });
        }

        function saveCheckOutData(placeName, address, checkOutAt, photoData, checkinid) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route("check-out.store") }}',
                method: 'POST',
                data: {
                    checkinid: checkinid,
                    place_name: placeName,
                    address: address,
                    check_out_loc: checkOutAt,
                    photo_data: photoData,
                    latitude: latLng.latitude,
                    longitude: latLng.longitude,
                },
                success: function (response) {
                    console.log('Data saved successfully:', response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Check-Out Successful',
                        text: 'Your Check-Out has been saved.',
                        confirmButtonText: 'OK',
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (error) {
                    console.error('Error saving Check-Out data:', error);
                }
            });
        }
    }
</script>
@endpush

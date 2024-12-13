@extends('layout.backend.app', [
    'title' => 'Attendance',
    'pageTitle' => 'Attendance',
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
    <div class="card-body d-flex flex-column align-items-center" style="min-height: 100vh;">
        <div id="map" style="width: 100%; height: 300px;"></div>

        <div id="video-container" class="mt-4 d-flex flex-column align-items-center">
            <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
            <video id="video" width="320" height="240" autoplay playsinline></video>

            <button id="click-photo" class="btn btn-primary mt-2"><i class="fas fa-camera"></i> Check IN</button>
        </div>
        <button id="toggle-camera" class="btn btn-primary"><i class='fas fa-street-view'></i> Start Checkin</button>
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
    let mapElement = document.querySelector('#map');
    let image_data_url;
    let latLng = {}; // To store latitude and longitude
    let checkInAt;

    document.addEventListener('DOMContentLoaded', function () {
        initMapAndLocation();
    });

    toggle_button.addEventListener('click', function () {
        startCamera();
    });

    click_button.addEventListener('click', function (e) {
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
        getLocationAndCheckIn(); // Proceed to send latitude and longitude
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

    // Initialize the map and show user's location
    function initMapAndLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    latLng.latitude = position.coords.latitude;
                    latLng.longitude = position.coords.longitude;
                    checkInAt = `${latLng.latitude}, ${latLng.longitude}`;

                    var map = new ol.Map({
                        target: 'map',
                        layers: [
                            new ol.layer.Tile({
                                source: new ol.source.OSM()
                            })
                        ],
                        view: new ol.View({
                            center: ol.proj.fromLonLat([latLng.longitude, latLng.latitude]),
                            zoom: 15
                        })
                    });

                    var marker = new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.fromLonLat([latLng.longitude, latLng.latitude]))
                    });

                    var markerStyle = new ol.style.Style({
                        image: new ol.style.Icon({
                            anchor: [0.5, 1],
                            src: 'https://cdn-icons-png.flaticon.com/512/149/149071.png',
                            scale: 0.1
                        })
                    });
                    marker.setStyle(markerStyle);

                    var vectorSource = new ol.source.Vector({
                        features: [marker]
                    });

                    var markerVectorLayer = new ol.layer.Vector({
                        source: vectorSource
                    });

                    map.addLayer(markerVectorLayer);
                },
                function (error) {
                    handleGeolocationError(error);
                },
                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
            );
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Geolocation Error',
                text: 'Geolocation is not supported by this browser.',
                confirmButtonText: 'OK'
            });
        }
    }

    function getLocationAndCheckIn() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '{{ route("check-in.store") }}',
            method: 'POST',
            data: {
                place_name: checkInAt, // Lat, Long combined
                address: "Dynamic Address Placeholder", // Optional
                check_in_loc: checkInAt,
                photo_data: image_data_url,
                latitude: latLng.latitude,
                longitude: latLng.longitude
            },
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-In Successful',
                    text: 'Your check-in has been saved.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Check-In Error',
                    text: 'Failed to save check-in data.',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
</script>
@endpush

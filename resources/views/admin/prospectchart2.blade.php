@extends('layout.backend.app',[
    'title' => 'Prospect',
    'pageTitle' =>'Prospect',
])

@push('css')
<link rel="stylesheet" href="{{ asset('openlayers/ol.css') }}">
    <style>
        #map {
            width: 100%;
            height: 400px;
        }
    #chart {
      position: absolute;
      top: 10px;
      left: 10px;
      z-index: 1000;
    }
    </style>
<script src="{{ asset('openlayers/dist/ol.js') }}"></script>  
    
@endpush

@section('content')


@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>

<script>
  // Initialize OpenLayers map
  const map = new ol.Map({
    target: 'map',
    layers: [
      new ol.layer.Tile({
        source: new ol.source.OSM(),
      }),
    ],
    view: new ol.View({
      center: ol.proj.fromLonLat([120, -5]),
      zoom: 5,
    }),
  });

  // Example geojson data for Indonesia's provinces
  const indonesiaGeoJson = {
    // Geojson data...
  };

  // Example data for the area chart
  const areaChartData = {
    labels: ['Province A', 'Province B', 'Province C', 'Province D'],
    datasets: [{
      label: 'Population',
      backgroundColor: 'rgba(255, 99, 132, 0.2)',
      borderColor: 'rgba(255, 99, 132, 1)',
      borderWidth: 1,
      data: [1000000, 2000000, 1500000, 1800000],
    }]
  };

  // Create Chart.js chart
  if (typeof ctx === 'undefined') {
    // Create Chart.js chart
    const ctx = document.getElementById('chart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: areaChartData,
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }
</script>
@endpush
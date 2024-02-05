@extends('layout.backend.app',[
    'title' => 'Prospect Chart',
    'pageTitle' =>'Prospect Chart',
])

@push('css')

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

    #container {
    height: 500px;
    min-width: 310px;
    max-width: 800px;
    margin: 0 auto;
}

.loading {
    margin-top: 10em;
    text-align: center;
    color: gray;
}


    </style>

<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>


@endpush

@section('content')
<div id="container"></div>



@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>

<script>


var jsonData;

$.ajax({
url: "{{ route('data.prospect') }}",
type: "POST",
data: { status: 1, url: "prospect" },
//async: false, // Make the call synchronous to ensure data is fetched before DataTable initialization
success: function (response) {
    jsonData = response; // Store the fetched data in the variable
   // console.log(jsonData);
   initializeChart(jsonData);
  },
error: function (xhr, status, error) {
    console.error("AJAX Error: " + status, error);
}
});


  // Initialize OpenLayers map
async function initializeChart(prosdata) {

  var datasementara = prosdata.data.reduce(function (r, row) {
    r[row.province.name] = ++r[row.province.name] || 1;
    return r;
  }, {});
  
  console.log(datasementara);

  var buname= prosdata.additionalData.buname;


  const topology = await fetch('../js/topoiss.json').then(response => response.json());

// Prepare demo data. The data is joined to map using value of 'hc-key'
// property by default. See API docs for 'joinBy' for more info on linking
// data and map.
const data = Object.keys(datasementara).map(function(provinceName) {
    return [provinceName, datasementara[provinceName]];
});

const topologyURL = '../js/topoiss.json';

// Create the chart
const chart = Highcharts.mapChart('container', {
    chart: {
        map: topology
    },

    title: {
        text: 'Peta Persebaran Data Jumlah Prospect'+buname
    },

    subtitle: {
        text: 'Source map: <a href="${topologyURL}">Indonesia</a>'
    },

    mapNavigation: {
        enabled: true,
        buttonOptions: {
            verticalAlign: 'bottom'
        }
    },

    colorAxis: {
        min: 0
    },

    series: [{
        data: data,
        name: 'Jumlah Prospect',
        states: {
            hover: {
                color: '#BADA55'
            }
        },
        dataLabels: {
            enabled: true,
            format: '{point.name}'
        },
        // Use point names instead of 'hc-key'
        keys: ['name', 'value'], // Assuming 'name' and 'value' are keys in your data array
        joinBy: 'name' // Join the data by the 'name' property
    }]
});
//const seriesData = chart.series;
  //  console.log(seriesData);
};

</script>
@endpush
@extends('layout.backend.app',[
	'title' => 'Welcome',
	'pageTitle' => 'Dashboard',
])
@section('content')
<div class="jumbotron">
  <h1 class="display-4">Hello, {{ Auth::user()->name }}</h1>
  <p class="lead">Ini adalah halaman simple dashboard.</p>
  <hr class="my-4">
  <p>Anda login sebagai {{ Auth::user()->role }}.</p>
  <p>Bagaimana Prospect anda hari ini ?</p>
  <p>Anda Belum Check In Lokasi Rumah Sakit Hari ini  123123</p>

</div>
@endsection
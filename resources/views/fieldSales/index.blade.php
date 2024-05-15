@extends('layout.backend.app',[
	'title' => 'Welcome',
	'pageTitle' => 'Dashboard',
])
@section('content')
<div class="jumbotron">
  <h1 class="display-4">Hello, {{ Auth::user()->name }}</h1>
  <p class="lead">Selamat Datang di ISBIS APPS</p>
  <hr class="my-4">
  <p>Anda login sebagai {{ strtoupper(Auth::user()->role) }}.</p>
  <p>Bagaimana Prospect anda hari ini ?</p>
  <p>Jangan Lupa Untuk Check In Lokasi Rumah Sakit Hari ini </p>

</div>
@endsection
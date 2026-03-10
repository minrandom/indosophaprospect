@extends('layout.backend.app',[
	'title' => 'Welcome',
	'pageTitle' => 'Welcome',
])
@section('content')
<style>
    .box-card {
    aspect-ratio: 1 / 1; /* perfect square */
    border-radius: 1.25rem;
}

.card-title-box {
    font-weight: 700;
    text-transform: uppercase;
     font-size: clamp(0.875rem, 2vw, 1.25rem); /* responsive font */
    word-break: break-word;
    line-height: 1.2;
}
</style>


<div class="jumbotron">
  <div style="font-size: 3rem">Hello, {{ Auth::user()->name }}</div>
  <p class="lead">Selamat Datang di ISBIS APPS</p>
  {{-- <hr class="my-4">
  <p>Anda login sebagai {{ strtoupper(Auth::user()->role) }}.</p>
  <p>Bagaimana Prospect anda hari ini ?</p>
  <p>Jangan Lupa Untuk Check In Lokasi Rumah Sakit Hari ini </p> --}}
</div>
  <div class="row">


    <div class="col-4 col-md-4 col-lg-3 mb-4">
        <a href="{{ route('admin.prospect.index')}}"
        class="text-white text-decoration-none d-block">

            <div class="card bg-primary text-white shadow h-100 box-card">
                <div class="card-body d-flex justify-content-center align-items-center text-center">
                    <div class="card-title-box">
                        Prospects
                    </div>
                </div>
            </div>

        </a>
    </div>

    <div class="col-4 col-md-4 col-lg-3 mb-4">
        <a href="{{ route('admin.installbase')}}"
        class="text-white text-decoration-none d-block">

            <div class="card bg-primary text-white shadow h-100 box-card">
                <div class="card-body d-flex justify-content-center align-items-center text-center">
                    <div class="card-title-box">
                        Installbase
                    </div>
                </div>
            </div>

        </a>
    </div>

    <div class="col-4 col-md-4 col-lg-3 mb-4">
        <a href="#"
        class="text-white text-decoration-none d-block">

            <div class="card bg-primary text-white shadow h-100 box-card">
                <div class="card-body d-flex justify-content-center align-items-center text-center">
                    <div class="card-title-box">
                        Mapping
                    </div>
                </div>
            </div>

        </a>
    </div>


            {{-- <div class="col-12 col-md-4 mb-4">
                <div class="embed-responsive embed-responsive-1by1">
                <div class="card bg-primary text-white shadow embed-responsive-item d-flex">
                    <div class="card-body d-flex justify-content-center align-items-center text-center">
                    <h2 class="font-weight-bold mb-0">...</h2>
                    </div>
                </div>
                </div>
            </div> --}}

</div>


@endsection

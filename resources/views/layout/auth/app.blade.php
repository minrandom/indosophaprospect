<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="icon" href="{{ asset('images/backend/issico.ico') }}">
    <title>{{ $title ?? 'Auth' }}</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('template/backend/sb-admin-2') }}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('template/backend/sb-admin-2') }}/css/sb-admin-2.min.css" rel="stylesheet">
   
<!-- Other head content -->



</head>

<body style="background-color: rgb(118,116,149);
  background-image: linear-gradient(185deg, rgba(118,116,149,1) 0%, rgba(40,40,180,1) 25%, rgba(91,108,164,1) 52%, rgba(28,184,215,1) 100%);
  background-size: cover;">

    <div class="container">
        @yield('content')
    </div>

   

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('template/backend/sb-admin-2') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('template/backend/sb-admin-2') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('template/backend/sb-admin-2') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('template/backend/sb-admin-2') }}/js/sb-admin-2.min.js"></script>

   

</body>

</html>
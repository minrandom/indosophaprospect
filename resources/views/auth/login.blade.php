@extends('layout.auth.app',[
    'title' => 'ISBIS APP'
])
@section('content')

    <script>
        window.onpageshow = function(event) {
            if (event.persisted) {
                // Page is loaded from cache (user pressed the back button)
                location.reload(true); // Reload the page
            }
        };
    </script>

<div class="row justify-content-center">

    <div class="col-xl-5 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                            <img src="{{ asset('images/backend/isslogo.png') }}" class="img-fluid" height="80" width="150" alt="">
                            <h1 class="h5 text-gray-900 mb-4" style="font-weight:bold">ISBIS APPS</h1>
                                <h1 class="h4 text-gray-600 mb-4">Login Form</h1>
                                @include('layout.component.alert-dismissible')
                            </div>
                            <form class="user" method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <div class="form-group">
                                    <input name="email" required="" type="email" class="form-control form-control-user"
                                        id="exampleInputEmail" aria-describedby="emailHelp"
                                        placeholder="Enter Email Address...">
                                </div>
                                <div class="form-group">
                                    <input name="password" required="" type="password" class="form-control form-control-user"
                                        id="exampleInputPassword" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="customCheck">
                                        <label class="custom-control-label" for="customCheck">Remember
                                            Me</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                            </form>
                            <hr>
                           <!-- <div class="text-center">
                                <a class="small" href="{{ route('forgot-password') }}">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('register') }}">Create an Account!</a>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@stop
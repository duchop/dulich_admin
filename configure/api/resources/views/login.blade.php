@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Admin - dulich' !!}@endSection

@section('body_class'){!! 'bg-dark' !!}@endSection

<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login</div>
        <div class="card-body">
            <form action="/api/login" method="POST">
            <span class="form-group" style="color: #ac2925">
              @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        {!! $error !!}
                    @endforeach
                @endif
            </span>
                <div class="form-group">
                    <div class="form-label-group">
                        <input type="email" id="inputEmail" class="form-control" placeholder="Email address"
                               required="required" autofocus="autofocus" name="email"
                               value="@if(isset($post_data['email'])) {{ $post_data['email'] }} @endif">
                        <label for="inputEmail">Email address</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-label-group">
                        <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                               required="required" name="password"
                               value="@if(isset($post_data['password'])) {{ $post_data['password'] }} @endif">
                        <label for="inputPassword">Password</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="remember-me">
                            Remember Password
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary btn-block">Login</button>
            </form>
            <div class="text-center">
                <a class="d-block small mt-3" href="register.html">Register an Account</a>
                <a class="d-block small" href="forgot-password.html">Forgot Password?</a>
            </div>
        </div>
    </div>
</div>

@section('script')
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
@endsection
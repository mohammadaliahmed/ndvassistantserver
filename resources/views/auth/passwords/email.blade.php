@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')


    <section id="main-home">
        <div class="main-home">
            <div class="main-img-area app">
                <div class="container">
                    <h1>Reset Password</h1>
                </div>
            </div>
        </div>
    </section>


    <section id="login">
        <div class="login">
            <div class="container">
                <div class="col-md-6">
                    <div class="login-area">
                        <div class="login-front">
                            <div>
                                <img width="250" src="{{asset('uploads')}}/full_logo.png"  alt="logo">
                                <p>
                                    {{$settings['footer_description']}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="login-text">
                        <form  method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            <h1>Reset Password</h1>

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Alert!</strong> {{ session('status') }}
                                </div>
                            @endif

                            @if(count($errors->all()))
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissable">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong>Alert!</strong> {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            <div class="form-group">

                                <input id="email" type="email" placeholder="Email Address" class="form-control" name="email" value="{{ old('email') }}" required>

                            <button type="submit" class="btn btn-default login-button">Send Password Reset Link</button>
                            <ul>
                                <li>
                                    <a href="{{asset('/password/reset')}}">Forget Your Password . ?</a>
                                </li>
                                <li>
                                    <a href="#">Need Support .</a>
                                </li>
                                <li>
                                    <a href="{{asset('/login')}}">Sign up .</a>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

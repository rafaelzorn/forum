@extends('layouts.master')

@section('content')
    <section class="box-form-login">
        <div class="login-header">
            <h5>Login</h5>
        </div>

        <form method="POST" action="{{ route('login') }}" class="form-horizontal">
            @csrf

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>

                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Keep logged in
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-lg btn-info btn-block btn-log-in m-b-30">Log In</button>

                    <a href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>

                    <p>Not registered? <a href="{{ route('register') }}">Register</a></p>
                </div>
            </div>
        </form>
    </section>
@endsection

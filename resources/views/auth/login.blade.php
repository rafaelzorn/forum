@extends('layouts.master')

@section('content')
    <section class="box-form-login">
        <div class="login-header">
            <h5>@lang('main.login')</h5>
        </div>

        <form method="POST" action="{{ route('login') }}" class="form-horizontal">
            @csrf

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="@lang('main.email')" required autofocus>

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="@lang('main.password')" required>

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
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('main.keep_logged_in')
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-lg btn-info btn-block btn-log-in m-b-30">@lang('main.log_in')</button>

                    <a href="{{ route('password.request') }}">
                        @lang('main.forgot_your_password')
                    </a>

                    <p>@lang('main.not_registered')? <a href="{{ route('register') }}">@lang('main.register')</a></p>
                </div>
            </div>
        </form>
    </section>
@endsection

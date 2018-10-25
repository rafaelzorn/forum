@extends('layouts.master')

@section('content')
    <section class="box-form-login">
        <div class="login-header">
            <h5>@lang('main.reset') @lang('main.password')</h5>
        </div>

        <form method="POST" action="{{ route('password.request') }}" class="form-horizontal">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" placeholder="@lang('main.email')" required autofocus>

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="@lang('main.password')" name="password" required>

                    @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="@lang('main.confirm') @lang('main.password')" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-lg btn-info btn-block btn-log-in m-b-30">@lang('main.reset') @lang('main.password')</button>
                </div>
            </div>
        </form>
    </section>
@endsection

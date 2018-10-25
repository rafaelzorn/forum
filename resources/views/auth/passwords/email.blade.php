@extends('layouts.master')

@section('content')
    <section class="box-form-login">
        <div class="login-header">
            <h5>@lang('main.reset') @lang('main.password')</h5>
        </div>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="form-horizontal">
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
                    <button type="submit" class="btn btn-lg btn-info btn-block btn-log-in m-b-30">@lang('main.send_password_reset_link')</button>
                </div>
            </div>
        </form>
    </section>
@endsection

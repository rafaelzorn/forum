@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">{{ $edit ? __('main.update') : __('main.new') }} @lang('main.user')</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card padding-10">
                <form method="POST" action="{{ $edit ? route('manager.users.update', $user->id) : route('manager.users.store') }}" class="form-horizontal">
                    @csrf

                    @if ($edit)
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name) }}" placeholder="@lang('main.name')" maxlength="255" required autofocus>

                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email) }}" placeholder="@lang('main.email')" maxlength="255" required>

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="" placeholder="@lang('main.password')" maxlength="255" {{ $edit ? null : ' required' }}>

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-3 col-12">
                            <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="@lang('main.confirm') @lang('main.password')" maxlength="255" {{ $edit ? null : ' required' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="is_admin" id="is_admin" value="1" @if (old('is_admin', $user->is_admin)) checked @endif>
                                    <label class="custom-control-label" for="is_admin">@lang('main.form_user.user_is_an_administrator')?</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-10 col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="active" id="active" value="1" @if (old('active', $user->active)) checked @endif>
                                    <label class="custom-control-label" for="active">@lang('main.active')</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">@lang('main.save')</button>
                    <a href="{{ route('manager.users.index') }}" class="btn btn-info">@lang('main.return')</a>
                </form>
            </div>
        </div>
    </div>
@endsection

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

                    <button type="submit" class="btn btn-success">@lang('main.save')</button>
                    <a href="{{ route('manager.users.index') }}" class="btn btn-info">@lang('main.return')</a>
                </form>
            </div>
        </div>
    </div>
@endsection

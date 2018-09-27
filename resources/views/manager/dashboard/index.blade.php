@extends('layouts.master')

@section('content')
<div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')

            <h4 class="page-title">@lang('main.dashboard')</h4>
        </div>
    </div>
@endsection

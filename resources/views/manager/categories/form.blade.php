@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">{{ $edit ? __('main.update') : __('main.new') }} @lang('main.category')</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card padding-10">
                <form method="POST" action="{{ $edit ? route('manager.categories.update', $category->id) : route('manager.categories.store') }}" class="form-horizontal">
                    @csrf

                    @if ($edit)
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $category->name) }}" placeholder="@lang('main.name')" maxlength="255" required autofocus>

                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="active" id="active" value="1" @if (old('active', $category->active)) checked @endif>
                                <label class="custom-control-label" for="active">@lang('main.active') (@lang('main.if_it_is_not_active'), @lang('main.form_category.category_can_not_be_used'))</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">@lang('main.save')</button>
                    <a href="{{ route('manager.categories.index') }}" class="btn btn-info">@lang('main.return')</a>
                </form>
            </div>
        </div>
    </div>
@endsection

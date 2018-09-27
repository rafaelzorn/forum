@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">{{ $edit ? __('main.update') : __('main.new') }} @lang('main.topic')</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card padding-10">
                <form method="POST" action="{{ $edit ? route('manager.topics.update', $topic->id) : route('manager.topics.store') }}" class="form-horizontal">
                    @csrf

                    @if ($edit)
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <div class="col-xs-12">
                            <select class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}" name="category_id" id="category_id" required autofocus>
                                <option value="">@lang('main.select') @lang('the') @lang('main.category')</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $topic->category_id) === $category->id ? 'selected' : null }}>{{ $category->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('category_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('category_id') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="name" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $topic->title) }}" maxlength="255" placeholder="@lang('main.title')" required>

                            @if ($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <textarea id="content" name="content" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="@lang('main.content')" required>{{ old('content', $topic->content) }}</textarea>

                            @if ($errors->has('content'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="active" id="active" value="1" @if (old('active', $topic->active)) checked @endif>
                                <label class="custom-control-label" for="active">@lang('main.active') (@lang('main.if_it_is_not_active'), @lang('main.form_topic.topics_will_not_appear_on_the_site'))</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">@lang('main.save')</button>
                    <a href="{{ route('manager.topics.index') }}" class="btn btn-info">@lang('main.return')</a>
                </form>
            </div>
        </div>
    </div>
@endsection

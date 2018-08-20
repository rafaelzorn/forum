@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">{{ $edit ? 'Update' : 'New' }} Topic</h4>
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
                                <option value="">Select the category</option>
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
                            <input id="name" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $topic->title) }}" placeholder="Title" required>

                            @if ($errors->has('title'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <textarea id="content" name="content" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="Content" required>{{ old('content', $topic->content) }}</textarea>

                            @if ($errors->has('content'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <select class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" name="active" id="active" required>
                                <option value="">Select the situation</option>
                                <option value="1" {{ old('active', $topic->active) === true ? 'selected' : null }}>Active</option>
                                <option value="0" {{ old('active', $topic->active) === false ? 'selected' : null }}>Inactive</option>
                            </select>

                            @if ($errors->has('active'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('active') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('manager.topics.index') }}" class="btn btn-info">Return</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">{{ $edit ? 'Update' : 'New' }} Category</h4>
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
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $category->name) }}" placeholder="Name" required autofocus>

                            @if ($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <select class="form-control{{ $errors->has('active') ? ' is-invalid' : '' }}" name="active" id="active" required>
                                <option value="">Select the situation</option>
                                <option value="1" {{ old('active', $category->active) === 1 ? 'selected' : null }}>Active</option>
                                <option value="0" {{ old('active', $category->active) === 0 ? 'selected' : null }}>Inactive</option>
                            </select>

                            @if ($errors->has('active'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('active') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Save</button>
                    <a href="{{ route('manager.categories.index') }}" class="btn btn-info">Return</a>
                </form>
            </div>
        </div>
    </div>
@endsection

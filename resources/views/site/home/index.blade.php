@extends('layouts.master')

@section('content')
    <div class="row m-t-30">
        <div class="col-lg-8 float-left">
            <div class="box-search">
                <input type="text" class="form-control input-search" autocomplete="off" name="search" placeholder="@lang('main.search')...">
                <button class="btn btn-search btn-success">@lang('main.search')</button>

                <div class="clearfix"></div>
            </div>

            @if ($topics->isNotEmpty())
                @foreach ($topics as $topic)
                    <a href="#" class="post-card card">
                        <div class="card-body">
                            <div>
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-12">
                                        <div class="box-initials">
                                            <span>R</span>
                                        </div>
                                    </div>

                                    <div class="box-post-info col-lg-11 col-md-11 col-sm-12">
                                        <h3>{{ $topic->title }}</h3>

                                        <span><b>@lang('main.category'):</b> {{ $topic->category->name }}</span>
                                        <span><b>@lang('main.by')</b> {{ $topic->user->name }} <b>@lang('main.in')</b> {{ $topic->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="content">
                                {{ $topic->content }}
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <p>@lang('messages.no_topics_found') :(</p>
            @endif
        </div>

        @if ($categories->isNotEmpty())
            <div class="col-lg-4 float-left">
                <div class="list-group">
                    @foreach ($categories as $category)
                        <a href="#" class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $category->name }}
                            <span class="badge badge-dark badge-pill">14</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

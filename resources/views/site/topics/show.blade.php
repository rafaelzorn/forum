@extends('layouts.master')

@section('content')
    <div class="row m-t-30">
        <div class="col-lg-12 m-b-15">
            <a href="{{ route('topics.index') }}"><< come back</a>
        </div>

        <div class="col-lg-8 float-left m-b-30">
            <div class="card">
                <div class="card-body topic-content">
                    <div class="topic-title m-b-30">
                        <h1>{{ $topic->title }}</h1>
                        <b>By</b> {{ $topic->user->name }} <b>in</b> {{ $topic->created_at->format('d/m/Y H:i') }}
                    </div>

                    <div class="topic-content-text">
                        <p>{{ $topic->content }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if ($topic->category->topics->whereNotIn('id', $topic->id)->isNotEmpty())
            <div class="col-lg-4 float-left related-topics">
                <h5>Tópicos Relacionados</h5>

                @foreach ($topic->category->topics->whereNotIn('id', $topic->id) as $topic)
                    <div class="card related-topic">
                        <div class="card-body topic-content">
                            <h5>{{ $topic->title }}</h5>
                            <div class="info">
                                <b>By</b> {{ $topic->user->name }} <b>in</b> {{ $topic->created_at->format('d/m/Y H:i') }}
                            </div>

                            <p>{{ $topic->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

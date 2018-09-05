@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">Topics</h4>
            <a href="{{ route('manager.topics.create') }}" class="btn btn-success btn-sm min-w-110">
                <i class="fa fa-plus"></i>
                New
            </a>
        </div>
    </div>

    <div class="row m-t-30">
        <div class="col-sm-12">
            <div class="card padding-10">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Category</th>
                            <th scope="col">Title</th>
                            <th scope="col">Active</th>
                            <th scope="col" style="width: 15%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($topics->isNotEmpty())
                            @foreach ($topics as $topic)
                                <tr>
                                    <td>{{ $topic->category->name }}</td>
                                    <td>{{ $topic->title }}</td>
                                    <td>{{ $topic->active }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('manager.topics.destroy', $topic->id) }}" class="form-horizontal">

                                        <a href="{{ route('manager.topics.edit', $topic->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </a>
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No topics found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

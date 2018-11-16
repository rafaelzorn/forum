@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')
            @include('layouts.includes.messages.error')
            @include('layouts.includes.messages.success')

            <h4 class="page-title">@lang('main.users')</h4>
            <a href="{{ route('manager.users.create') }}" class="btn btn-success btn-sm min-w-110">
                <i class="fa fa-plus"></i>
                @lang('main.new')
            </a>
        </div>
    </div>

    <div class="row m-t-30">
        <div class="col-sm-12">
            <div class="card padding-10">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th scope="col" style="width: 15%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($users->isNotEmpty())
                            @foreach ($users as $user)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <form method="POST" action="{{ route('manager.users.destroy', $user->id) }}" class="form-horizontal">

                                        <a href="{{ route('manager.users.edit', $user->id) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-edit"></i>
                                            @lang('main.edit')
                                        </a>
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                                @lang('main.delete')
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">@lang('messages.no_users_found')</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

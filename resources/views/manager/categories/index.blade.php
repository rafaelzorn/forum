@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.includes.messages.warning')

            <h4 class="page-title">Categories</h4>
            <a href="#" class="btn btn-success btn-sm min-w-110">New</a>
        </div>
    </div>

    <div class="row m-t-30">
        <div class="col-sm-12">
            <div class="card padding-10">
                <table class="table table-hover table-sm table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Active</th>
                            <th scope="col" style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Teste 1</td>
                            <td>Ativo</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">Editar</a>
                                <a href="#" class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Teste 2</td>
                            <td>Ativo</td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">Editar</a>
                                <a href="#" class="btn btn-danger btn-sm">Excluir</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

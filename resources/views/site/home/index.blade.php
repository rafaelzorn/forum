@extends('layouts.master')

@section('content')
    <div class="row m-t-30">
        <div class="col-lg-8 float-left">
            <div class="box-search">
                <input type="text" class="form-control input-search" autocomplete="off" name="search" placeholder="Search...">
                <button class="btn btn-search btn-success">Search</button>

                <div class="clearfix"></div>
            </div>

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
                                <h3>Discussão Vingadores</h3>

                                <span><b>Category:</b> Filmes</span>
                                <span><b>By</b> Rafael Zorn <b>in</b> 30/09/2018</span>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        Mussum Ipsum, cacilds vidis litro abertis. Paisis, filhis, espiritis santis. In elementis mé pra quem é amistosis quis leo. Quem manda na minha terra sou euzis! Nec orci ornare consequat. Praesent lacinia ultrices consectetur. Sed non ipsum felis.
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-4 float-left">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Filmes
                    <span class="badge badge-dark badge-pill">14</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center  active">
                    Quadrinhos
                    <span class="badge badge-dark badge-pill">14</span>
                </li>
            </ul>
        </div>
    </div>
@endsection

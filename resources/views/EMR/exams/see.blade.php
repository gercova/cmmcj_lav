@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Ver Exámenes')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listado de exámenes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('emr.exams.home') }}">Exámenes</a></li>
                        <li class="breadcrumb-item active">Listado</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Información del paciente
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <p>Menu de registros de exámenes {{ $history->sexo == 'M' ? 'del' : 'de la' }} paciente <b>{{ $history->nombres }}</b> identificado con DNI: <b>{{ $history->dni }}</b></p>
                                    </div>
                                    <input type="hidden" id="historyId" name="historyId" value="{{ $history->id }}">
                                    <input type="hidden" id="dni" name="dni" value="{{ $history->dni }}">
                                    @can('examen_crear')
                                        <hr>
                                        <a class="btn btn-outline btn-primary" href="{{ route('emr.exams.new', $history->id) }}">Agregar nuevo examen</a>
                                    @endcan
                                    <hr>
                                </div>

                                <div class="col-12">
                                    <div class="card card-primary card-tabs">
                                        <div class="card-header p-0 pt-1">
                                            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="tab-exams" data-toggle="pill" href="#exams" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Exámenes</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="tab-bloodtest" data-toggle="pill" href="#bloodtest" role="tab" aria-controls="bloodtest" aria-selected="false">Examen de sangre</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="tab-urinetest" data-toggle="pill" href="#urinetest" role="tab" aria-controls="urinetest" aria-selected="false">Examen de orina</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="tab-stooltest" data-toggle="pill" href="#stooltest" role="tab" aria-controls="stooltest" aria-selected="false">Examen de heces</a>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                                <div class="tab-pane fade active show" id="exams" role="tabpanel" aria-labelledby="tab-exams">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table id="exam_data" class="table table-striped table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Fecha</th>
                                                                            <th>Tipo Examen</th>
                                                                            <th>Opciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="bloodtest" role="tabpanel" aria-labelledby="tab-bloodtest">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="bloodtest_data" class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Fecha</th>
                                                                            <th>Detalle</th>
                                                                            <th>Fecha del examen</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="urinetest" role="tabpanel" aria-labelledby="tab-urinetest">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="urinetest_data" class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Fecha</th>
                                                                            <th>Detalle</th>
                                                                            <th>Fecha del examen</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="stooltest" role="tabpanel" aria-labelledby="tab-stooltest">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table id="stooltest_data" class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Fecha</th>
                                                                            <th>Detalle</th>
                                                                            <th>Fecha del examen</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/exams.js') }}"></script>
<script src="{{ asset('js/modalFormTest.js') }}"></script>
@endsection
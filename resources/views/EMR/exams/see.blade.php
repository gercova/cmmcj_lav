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
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table id="exam_data" class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Fecha</th>
                                                        <th>DNI</th>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/exams.js') }}"></script>
@endsection
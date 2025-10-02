@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Inicio') <!-- Título dinámico -->
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('home') }} ">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $hc }}</h3>
                            <p>Historias</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-file-medical-fill"></i>
                        </div>
                        <a href="{{ route('emr.histories.home') }}" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $ex }}</h3>
                            <p>Exámenes</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-file-medical-fill"></i>
                        </div>
                        <a href="{{ route('emr.exams.home') }}" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $us }}</h3>
                            <p>Usuarios</p>
                        </div>
                        <div class="icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <a href="{{ route('security.users.home') }}" class="small-box-footer">Ver más <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('emr.histories.new') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar nueva historia</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <h3>Pacientes agendados</h3>
                                <table class="table table-hover" id="quotes_data">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:10px">#</th>
                                            <th>DNI</th>
                                            <th>Nombres</th>
                                            <th>Fecha</th>
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
    </section>
</div>
<script src="{{ asset('js/home.js') }}"></script>
@endsection

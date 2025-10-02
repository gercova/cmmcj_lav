@extends('layouts.skelenton')
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
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="bi bi-person-lines-fill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Historias</span>
                            <span class="info-box-number" id="historiesCount"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="bi bi-file-medical-fill"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Exámenes</span>
                            <span class="info-box-number" id="examsCount"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="bi bi-file-earmark-medical-fill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Citas</span>
                                <span class="info-box-number" id="appointmentsCount"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Historias diarias</span>
                                <span class="info-box-number" id="dailyQuotesCount"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reporte de historias clínicas</h3>
                            <div class="card-tools">
                                <select name="year" id="year" class="form-control">
                                    @foreach($yr as $y)
                                        <option value="{{ $y->year }}">{{ $y->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div id="histories" style="margin: 0 auto"></div>
                            </div>
                            <!--<div class="col-md-12">
                                <div id="exams" style="margin: 0 auto"></div>
                            </div>
                            <div class="col-md-12">
                                <div id="appointments" style="margin: 0 auto"></div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reporte de Diagnósticos</h3>
                        </div>
                        <div class="card-body">
                            <div id="diagnosisByExams" style="margin: 0 auto"></div>
                        </div>        
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Reporte de Recetas</h3>
                        </div>
                        <div class="card-body">
                            <div id="medicationsByExams" style="margin: 0 auto"></div>
                        </div>        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="historiesBySex" style="margin: 0 auto"></div>
                        </div>
                    </div> 
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="historiesByMaritalStatus" style="margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="historiesByBloodingGroup" style="margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="historiesByDegreeIntruction" style="margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="historiesBySmoking" style="margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
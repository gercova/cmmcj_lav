@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Hospitalizaciones') <!-- Título dinámico -->
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Hospitalizaciones</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Hospitalizaciones</li>
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
                                <a href="{{ route('emr.histories.new') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Agregar nueva historia</a>
                            </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-responsive" id="histories"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/hospitalizations.js') }}"></script>
@endsection
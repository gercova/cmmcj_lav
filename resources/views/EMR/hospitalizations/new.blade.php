@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Crear Hospitalización') <!-- Título dinámico -->
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Nueva hospitalización</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('emr.hospitalizations.home') }}">Hospitalizaciones</a></li>
                            <li class="breadcrumb-item active">Nueva hospitalización</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header"></div>
                    <form id="hospitalizationForm" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Atendido por:</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="{{ auth()->user()->name }}" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Especialidad:</label>
                                        <div class="col-sm-8">
                                            <input type="text" value="{{ auth()->user()->roles[0]->name }}" class="form-control form-control-sm" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Fecha:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm date" name="created_at" value="{{ now()->format('Y-m-d') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DNI :: NOMBRES </label>
                                        <input type="text" class="form-control" value="{{ $history->dni.' :: '.strtoupper($history->nombres) }}" readonly>
                                        <input type="hidden" name="historia_id" id="historia_id" value="{{ $history->id }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bed_id">Cama: </label>
                                        <select name="bed_id" id="bed_id">
                                            <option value="">-- Seleccione --</option>
                                            @foreach($beds as $b)
                                                <option value="{{ $b->id }}">{{ $b->description.' - '.$b->floor }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="fc">Frecuencia cardiaca</label>
                                        <input class="form-control" id="fc" name="fc" value="{{ old('fc') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="t">Temperatura C / F</label>
                                        <input class="form-control" id="t" name="t" value="{{ old('t') }}" required>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="so2">Saturación de oxígeno</label>
                                        <input class="form-control" id="so2" name="so2" value="{{ old('so2') }}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="vital_functions">Funciones vitales</label>
                                        <input type="text" class="form-control" name="vital_functions" id="vital_functions" value="{{ old('vital_functions') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="observations">Observaciones</label>
                                        <input type="text" class="form-control" name="observations" id="observations" value="{{ old('observations') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="others">Otros hallazgos</label>
                                        <textarea class="form-control" name="others" id="others">{{ old('others') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script>
        new SlimSelect({
            select: '#bed_id',
            placeholder: 'Seleccione una cama',
            allowDeselect: true
        });
    </script>
    <script src="{{ asset('js/hospitalizations.js') }}"></script>
@endsection
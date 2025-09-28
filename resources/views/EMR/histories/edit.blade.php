@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Actualizar Historia') <!-- Título dinámico -->
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Actualizar historia</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('emr.histories.home') }}">Historias</a></li>
                            <li class="breadcrumb-item active">Actualizar historia</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Filiación</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <form id="historyForm" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ $history->id }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="tipo_documento_id">Tipo Documento</label>
                                        <select class="form-control" id="tipo_documento_id" name="tipo_documento_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($td as $t)
                                                <option value="{{ $t->id }}" {{ $history->tipo_documento_id == $t->id ? 'selected' : '' }}>{{ $t->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="dni">DNI</label>
                                        <input type="text" class="form-control" id="dni" name="dni" value="{{ $history->dni }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" class="form-control" id="nombres" name="nombres" value="{{ $history->nombres }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                        <input type="text" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ $history->fecha_nacimiento->format('Y-m-d') }}" onchange="getAge(this.value);" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="edad">Edad</label>
                                        <input type="text" class="form-control" id="edad" name="edad" value="{{ $oc[0]['age'] }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="sexo">Sexo</label>
                                        <select class="form-control" id="sexo" name="sexo" required>
                                            <option value="">-- Seleccione --</option>
                                            <option value="M" {{ $history->sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                                            <option value="F" {{ $history->sexo == 'F' ? 'selected' : '' }}>Femenino</option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="telefono">Celular</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $history->telefono }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $history->email }}" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="grupo_sanguineo_id">Grupo sanguíneo</label>
                                        <select class="form-control" id="grupo_sanguineo_id" name="grupo_sanguineo_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($gs as $g)
                                                <option value="{{ $g->id }}" {{ $history->grupo_sanguineo_id == $g->id ? 'selected' : '' }}>{{ $g->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                @if($history->ubigeo_extranjero !== null)
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="extranjero">Ubigeo extranjero</label>
                                            <input class="form-control" id="extranjero" name="extranjero" value="{{ $history->ubigeo_extranjero }}" placeholder="PAÍS, REGIÓN, CIUDAD">
                                            
                                        </div>
                                    </div>
                                @else
                                    <div class="col-2">
                                        <div class="form-group">
                                            <br>
                                            <button type="button" class="btn btn-warning extra"><i class="fa fa-globe"></i> Paciente extranjero</button>
                                            <button type="button" class="btn btn-success pe d-none"><i class="fa fa-globe"></i> Paciente nacional</button>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group nacional">
                                            <label for="ubigeo_nacimiento">Lugar Nacimiento: </label>
                                            <select class="form-control buscarUbigeoR" id="ubigeo_nacimiento" name="ubigeo_nacimiento">
                                                <option value="{{ $un[0]['nacimiento'] }}"></option>
                                            </select>
                                        </div>
                                        <div class="form-group foreign d-none">
                                            <label for="extranjero">Ubigeo extranjero</label>
                                            <input class="form-control" id="extranjero" name="extranjero" value="{{ $history->ubigeo_extranjero }}" placeholder="PAÍS, REGIÓN, CIUDAD">
                                        </div>
                                    </div>
                                @endif
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="ubigeo_residencia">Lugar Residencia: </label>
                                        <select class="form-control buscarUbigeoR" id="ubigeo_residencia" name="ubigeo_residencia" required>
                                            <option value="{{ $ur[0]['residencia'] }}"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $history->direccion }}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="grado_instruccion_id">Grado instrucción</label>
                                        <select class="form-control" id="grado_instruccion_id" name="grado_instruccion_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($gi as $g)
                                                <option value="{{ $g->id }}" {{ $history->grado_instruccion_id == $g->id ? 'selected' : '' }}>{{ $g->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="ocupacion_id">Ocupación</label>
                                        <select class="form-control buscarOcupacion" id="ocupacion_id" name="ocupacion_id" required>
                                            <option value="{{ $oc[0]['ocupacion'] }}"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="estado_civil_id">Estado civil</label>
                                        <select class="form-control" id="estado_civil_id" name="estado_civil_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($ec as $e)
                                                <option value="{{ $e->id }}" {{ $history->estado_civil_id == $e->id ? 'selected' : '' }}>{{ $e->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="acompanante">Acompañante</label>
                                        <input class="form-control" id="acompanante" name="acompanante" value="{{ $history->acompanante }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="acompanante_telefono">Acompañante teléfono</label>
                                        <input class="form-control" id="acompanante_telefono" name="acompanante_telefono" value="{{ $history->acompanante_telefono }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="acompanante_direccion">Acompañante dirección</label>
                                        <input class="form-control" id="acompanante_direccion" name="acompanante_direccion" value="{{ $history->acompanante_direccion }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="vinculo">Vínculo</label>
                                        <input class="form-control" id="vinculo" name="vinculo" value="{{ $history->vinculo }}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="seguro_id">Seguro</label>
                                        <select class="form-control" id="seguro_id" name="seguro_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($ss as $s)
                                                <option value="{{ $s->id }}" {{ $history->seguro_id == $s->id ? 'selected' : '' }}>{{ $s->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="seguro_descripcion">Descripción</label>
                                        <input type="text" class="form-control" name="seguro_descripcion" id="seguro_descripcion" value="{{ $history->seguro_descripcion }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @role('Administrador|Especialista')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_quirurgicos">Antecedentes quirúrgicos</label>
                                            <textarea class="form-control" name="ant_quirurgicos" id="ant_quirurgicos">{{ $history->ant_quirurgicos }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_patologicos">Antecedentes patológicos</label>
                                            <textarea class="form-control" name="ant_patologicos" id="ant_patologicos">{{ $history->ant_patologicos }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_familiares">Antecedentes familiares</label>
                                            <textarea class="form-control" name="ant_familiares" id="ant_familiares">{{ $history->ant_familiares }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_medicos">Antecedentes médicos</label>
                                            <textarea class="form-control" name="ant_medicos" id="ant_medicos">{{ $history->ant_medicos }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="rams">RAMS</label>
                                            <textarea class="form-control" name="rams" id="rams">{{ $history->rams }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endrole
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
    <script src="{{ asset('js/histories.js') }}"></script>
@endsection
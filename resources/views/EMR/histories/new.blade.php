@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Crear Historia') <!-- Título dinámico -->
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Nueva historia</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('emr.histories.home') }}">Historias</a></li>
                            <li class="breadcrumb-item active">Nueva historia</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Filiación</h3>
                    </div>
                    <form id="historyForm" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="tipo_documento_id">Tipo Documento</label>
                                        <select class="form-control form-control-sm" id="tipo_documento_id" name="tipo_documento_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($td as $t)
                                                <option value="{{ $t->id }}" {{ old('tipo_documento_id') == $t->id ? 'selected' : '' }}>{{ $t->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="dni">DNI</label>
                                        <input type="number" class="form-control form-control-sm" id="dni" name="dni" value="{{ old('dni') }}" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="nombres">Nombres</label>
                                        <input type="text" class="form-control form-control-sm" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                        <input type="text" class="form-control form-control-sm" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" onchange="getAge(this.value);" required>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="edad">Edad</label>
                                        <input type="text" class="form-control form-control-sm" id="edad" name="edad" value="{{ old('edad') }}" required readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="sexo">Sexo</label>
                                        <select class="form-control form-control-sm" id="sexo" name="sexo" required>
                                            <option value="">-- Seleccione --</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="telefono">Celular</label>
                                        <input type="text" class="form-control form-control-sm" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="grupo_sanguineo_id">Grupo sanguíneo</label>
                                        <select class="form-control form-control-sm" id="grupo_sanguineo_id" name="grupo_sanguineo_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($gs as $g)
                                                <option value="{{ $g->id }}" {{ old('grupo_sanguineo_id') == $g->id ? 'selected' : '' }}>{{ $g->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
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
                                        <select class="form-control form-control-sm buscarUbigeoR" id="ubigeo_nacimiento" name="ubigeo_nacimiento"></select>
                                    </div>
                                    <div class="form-group foreign d-none">
                                        <label for="extranjero">Ubigeo extranjero</label>
                                        <input class="form-control form-control-sm" id="extranjero" name="extranjero" placeholder="PAÍS, REGIÓN, CIUDAD">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="ubigeo_residencia">Lugar Residencia: </label>
                                        <select class="form-control form-control-sm buscarUbigeoR" id="ubigeo_residencia" name="ubigeo_residencia" required></select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="direccion">Dirección</label>
                                        <input type="text" class="form-control form-control-sm" id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="grado_instruccion_id">Grado instrucción</label>
                                        <select class="form-control form-control-sm" id="grado_instruccion_id" name="grado_instruccion_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($gi as $g)
                                                <option value="{{ $g->id }}" {{ old('grado_instruccion_id') == $g->id ? 'selected' : '' }}>{{ $g->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="ocupacion_id">Ocupación</label>
                                        <select class="form-control form-control-sm buscarOcupacion" id="ocupacion_id" name="ocupacion_id" required></select>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="estado_civil_id">Estado civil</label>
                                        <select class="form-control form-control-sm" id="estado_civil_id" name="estado_civil_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($ec as $e)
                                                <option value="{{ $e->id }}" {{ old('estado_civil_id') == $e->id ? 'selected' : '' }}>{{ $e->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="acompanante">Acompañante</label>
                                        <input class="form-control form-control-sm" id="acompanante" name="acompanante" value="{{ old('acompanante') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="acompanante_telefono">Acompañante teléfono</label>
                                        <input class="form-control form-control-sm" id="acompanante_telefono" name="acompanante_telefono" value="{{ old('acompanante_telefono') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="acompanante_direccion">Acompañante dirección</label>
                                        <input class="form-control form-control-sm" id="acompanante_direccion" name="acompanante_direccion" value="{{ old('acompanante_direccion') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="vinculo">Vínculo</label>
                                        <input class="form-control form-control-sm" id="vinculo" name="vinculo" value="{{ old('vinculo') }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="seguro_id">Seguro</label>
                                        <select class="form-control form-control-sm" id="seguro_id" name="seguro_id" required>
                                            <option value="">-- Seleccione --</option>
                                            @foreach($ss as $s)
                                                <option value="{{ $s->id }}" {{ old('seguro_id') == $s->id ? 'selected' : '' }}>{{ $s->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <label for="seguro_descripcion">Descripción</label>
                                        <input type="text" class="form-control form-control-sm" name="seguro_descripcion" id="seguro_descripcion" value="{{ old('seguro_descripcion') }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @role('administrador|especialista')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_quirurgicos">Antecedentes quirúrgicos</label>
                                            <textarea class="form-control form-control-sm" name="ant_quirurgicos" id="ant_quirurgicos">{{ old('ant_quirurgicos') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_patologicos">Antecedentes patológicos</label>
                                            <input type="text" class="form-control form-control-sm" name="ant_patologicos" id="ant_patologicos" value="{{ old('ant_patologicos') }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_familiares">Antecedentes familiares</label>
                                            <input type="text" class="form-control form-control-sm" name="ant_familiares" id="ant_familiares" value="{{ old('ant_familiares') }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="ant_medicos">Antecedentes médicos</label>
                                            <input type="text" class="form-control form-control-sm" name="ant_medicos" id="ant_medicos" value="{{ old('ant_medicos') }}">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="rams">RAMS</label>
                                            <textarea class="form-control form-control-sm" name="rams" id="rams">{{ old('rams') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endrole

                        </div>
                        <div class="card-footer">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('js/histories.js') }}"></script>
@endsection

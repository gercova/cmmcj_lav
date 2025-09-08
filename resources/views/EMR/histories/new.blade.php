@extends('layouts.skeleton')
@section('title', $enterprise[0]->nombre_comercial. ' - Crear Historia') <!-- Título dinámico -->
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
                        <li class="breadcrumb-item"><a href="{{ route('hcl.histories.home') }}">Historias</a></li>
                        <li class="breadcrumb-item active">Nueva historia</li>
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
                <form method="post" id="formHC">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="id_td">Tipo Documento</label>
                                    <select class="form-control" id="id_td" name="id_td" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($dt as $t)
                                            <option value="{{ $t->id }}" {{ old('id_td') == $t->id ? 'selected' : '' }}>{{ $t->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="dni">DNI</label>
                                    <input type="number" class="form-control" id="dni" name="dni" value="{{ old('dni') }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nombres">Nombres</label>
                                    <input type="text" class="form-control" id="nombres" name="nombres" value="{{ old('nombres') }}" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" onchange="getAge(this.value);" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="age">Edad</label>
                                    <input type="text" class="form-control" id="age" name="age" value="{{ old('age') }}" required readonly>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="id_sexo">Sexo</label>
                                    <select class="form-control" id="id_sexo" name="id_sexo" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($sx as $s)
                                            <option value="{{ $s->id }}" {{ old('id_sexo') == $s->id ? 'selected' : '' }}>{{ $s->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="telefono">Celular</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="id_gs">Grupo sanguíneo</label>
                                    <select class="form-control" id="id_gs" name="id_gs" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($bg as $b)
                                            <option value="{{ $b->id }}" {{ old('id_gs') == $b->id ? 'selected' : '' }}>{{ $b->descripcion }}</option>
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
                                    <select class="form-control buscarUbigeoR" id="ubigeo_nacimiento" name="ubigeo_nacimiento"></select>
                                </div>
                                <div class="form-group foreign d-none">
                                    <label for="extranjero">Ubigeo extranjero</label>
                                    <input class="form-control" id="extranjero" name="extranjero" placeholder="PAÍS, REGIÓN, CIUDAD">
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="ubigeo_residencia">Lugar Residencia: </label>
                                    <select class="form-control buscarUbigeoR" id="ubigeo_residencia" name="ubigeo_residencia" required></select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="id_gi">Grado instrucción</label>
                                    <select class="form-control" id="id_gi" name="id_gi" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($di as $g)
                                            <option value="{{ $g->id }}">{{ $g->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="id_ocupacion">Ocupación</label>
                                    <select class="form-control buscarOcupacion" id="id_ocupacion" name="id_ocupacion" required></select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="id_estado">Estado civil</label>
                                    <select class="form-control" id="id_estado" name="id_estado" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($ms as $m)
                                            <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @role('administrador|especialista')
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="surgeries">Cirugías</label>
                                        <input type="text" class="form-control" name="surgeries" id="surgeries" value="{{ old('surgeries') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="transfusions">Transfusiones sanguíneas</label>
                                        <input type="text" class="form-control" name="transfusions" id="transfusions" value="{{ old('transfusions') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="trauma">Traumatismos</label>
                                        <input type="text" class="form-control" name="trauma" id="trauma" value="{{ old('trauma') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="hospitalizations">Hospitalizaciones previas</label>
                                        <input type="text" class="form-control" name="hospitalizations" id="hospitalizations" value="{{ old('hospitalizations') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="drugs">Consumo de drogas sociales</label>
                                        <input type="text" class="form-control" name="drugs" id="drugs" value="{{ old('drugs') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="fh">Antecedentes familiares</label>
                                        <input type="text" class="form-control" name="fh" id="fh" value="{{ old('fh') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="bs">Estado basal</label>
                                        <input type="text" class="form-control" name="bs" id="bs" value="{{ old('bs') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="medication">RAMS</label>
                                        <input type="text" class="form-control" name="medication" id="medication" value="{{ old('medication') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="animals">Animales en casa</label>
                                        <input type="text" class="form-control" name="animals" id="animals" value="{{ old('animals') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="otros">Otros</label>
                                        <textarea class="form-control" cols="96" name="others" id="otros">{{ old('others') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="ba">Asma bronquial</label>
                                        <input type="text" class="form-control" name="ba" id="ba" value="{{ old('ba') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="epoc">EPOC</label>
                                        <input type="text" class="form-control" name="epoc" id="epoc" value="{{ old('epoc') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="epid">EPID</label>
                                        <input type="text" class="form-control" name="epid" id="epid" value="{{ old('epid') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pt">Tuberculosis</label>
                                        <input type="text" class="form-control" name="pt" id="pt" value="{{ old('pt') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="lg">Cáncer al pulmón</label>
                                        <input type="text" class="form-control" name="lg" id="lg" value="{{ old('lg') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pe">Efusión pleural</label>
                                        <input type="text" class="form-control" name="pe" id="pe" value="{{ old('pe') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="pneumonia">Neumonías</label>
                                        <input type="text" class="form-control" name="pneumonia" id="pneumonia" value="{{ old('pneumonia') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="smoking">Tabaquismo</label>
                                        <input type="text" class="form-control" name="smoking" id="smoking" value="{{ old('smoking') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="tipo">Seleccione tipo de consumo de tabaco</label>
                                        <select class="form-control" name="tipo" id="tipo">
                                            <option value="">-- Seleccione --</option>
                                            @foreach($tb as $t)
                                                <option value="{{ $t->id }}">{{ $t->consumo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="cig">Cigarros x día</label>
                                        <input type="number" class="form-control" name="cig" min="0" value="0" id="cig" value="{{ old('cig') }}">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="af">Años fumando</label>
                                        <input type="number" class="form-control" name="af" min="0" value="0" id="af" value="{{ old('af') }}">
                                    </div>
                                </div>
                                <div class="col-1">
                                    <div class="form-group">
                                        <label for="r">Resultado</label>
                                        <input type="number" class="form-control" name="r" min="0" step="any" value="0" id="r" value="{{ old('r') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="tbcc">Contacto TBC / COVID</label>
                                        <input type="text" class="form-control" name="tbcc" id="tbcc" value="{{ old('tbcc') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="be">Exposición a biomasa</label>
                                        <input type="text" class="form-control" name="be" id="be" value="{{ old('be') }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rfc">Motivo consulta</label>
                                        <input type="text" class="form-control" name="rfc" id="rfc" value="{{ old('rfc') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="cs">Síntomas cardinales</label>
                                        <input type="text" class="form-control" name="cs" id="cs" value="{{ old('cs') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="te">TE</label>
                                        <input type="text" class="form-control" name="te" id="te" value="{{ old('te') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="fi">FI</label>
                                        <input type="text" class="form-control" name="fi" id="fi" value="{{ old('fi') }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="c">C</label>
                                        <input type="text" class="form-control" name="c" id="c" value="{{ old('c') }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="ca">Relato cronológico</label>
                                        <textarea class="form-control" cols="96" name="ca" id="ca" value="{{ old('ca') }}"></textarea>
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
@endsection

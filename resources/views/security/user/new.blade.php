@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Crear Nuevo Usuario')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Seguridad</li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Datos del usuario</h3>
                </div>
                <form method="post" id="userForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="name">Nombres del usuario: </label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="role_id">Rol: </label>
                                            <select class="form-control" id="role_id" name="role_id">
                                                <option value="">-- Seleccione --</option>
                                                @foreach ($rl as $r)
                                                    <option value="{{ $r->id }}" {{ old('role_id') == $r->id ? 'selected' : '' }}>{{ $r->name }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="especialidad_id">Especialidad: </label>
                                            <select class="form-control" id="especialidad_id" name="especialidad_id">
                                                <option value="">-- Seleccione --</option>
                                                @foreach ($sp as $s)
                                                    <option value="{{ $s->id }}" {{ old('especialidad_id') == $s->id ? 'selected' : '' }}>{{ $s->descripcion }}</option> 
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="avatar">Avatar: </label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image-input" name="avatar" accept="image/*">
                                                <label class="custom-file-label" for="avatar">Elegir archivo</label>
                                            </div>
                                        </div>
                                        <div class="form-group text-center">
                                            <img id="image-preview" src="{{ asset('storage/img/usuarios/default/anonymous.png') }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        </div>
                                    </div>
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
<script src="{{ asset('js/users.js') }}"></script>
@endsection
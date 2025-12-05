@extends('layouts.skelenton')
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
                    <h3 class="card-title">Datos del usuario <b>{{ $user->name }}</b></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-5 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-user-tab" data-toggle="pill" href="#vert-tabs-user" role="tab" aria-controls="vert-tabs-user" aria-selected="true">Actualizar información general</a>
                                <a class="nav-link" id="vert-tabs-userpassword-tab" data-toggle="pill" href="#vert-tabs-userpassword" role="tab" aria-controls="vert-tabs-userpassword" aria-selected="false">Actualizar contraseña del usuario</a>
                            </div>
                        </div>
                        <div class="col-7 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade active show" id="vert-tabs-user" role="tabpanel" aria-labelledby="vert-tabs-user-tab">
                                    <form method="post" id="userForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="name">Nombres del usuario: </label>
                                                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                                                        <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="username">Alias del usuario: </label>
                                                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="email">Email: </label>
                                                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="form-group">
                                                        <label for="role_id">Rol del usuario: </label>
                                                        <select class="form-control" id="role_id" name="role_id">
                                                            <option value="">-- Seleccione --</option>
                                                            @foreach ($rl as $r)
                                                                <option value="{{ $r->id }}" {{ $ur[0]->role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
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
                                                                <option value="{{ $s->id }}" {{ $user->especialidad_id == $s->id ? 'selected' : '' }}>{{ $s->especialidad.' - '.$s->ocupacion }}</option>
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
                                                        <img id="image-preview" src="{{ $user->profile_photo_url }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="vert-tabs-userpassword" role="tabpanel" aria-labelledby="vert-tabs-userpassword-tab">
                                    <form id="passwordForm" method="post">
                                        @csrf
                                        <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="alert alert-warning" role="alert">
                                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                                        <strong>Advertencia:</strong>
                                                        Al cambiar la contraseña, se perderá el acceso al sistema. Por favor, asegúrese de recordar la nueva contraseña.
                                                    </div>
                                                    <label for="password">Nueva contraseña: </label>
                                                    <input type="password" class="form-control" id="password" name="password">
                                                    <label for="password_confirmation">Confirmar contraseña: </label>
                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('security.users.home') }}" class="btn btn-danger"><i class="bi bi-box-arrow-left"></i> Volver</a>&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/users.js') }}"></script>
@endsection

@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Permisos') <!-- Título dinámico -->
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Permisos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('home') }} ">Home</a></li>
                        <li class="breadcrumb-item">Seguridad</li>
                        <li class="breadcrumb-item active">Permisos</li>
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
                        @can('permiso_crear')
                            <div class="card-header">
                                <button type="button" class="btn btn-outline btn-primary" id="btn-add-permission">
                                    <i class="bi bi-plus-circle"></i> Agregar permiso
                                </button>
                            </div>
                        @endcan
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="permission_data">
                                    <thead>
                                        <tr>
                                            <th style="width:10px">#</th>
                                            <th>Permiso</th>
                                            <th>Guardia</th>
                                            <th>Módulo / Súbmodulo</th>
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
@can('permiso_crear')
    <div class="modal fade" id="modalPermission" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="permissionForm" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group module">
                            <label for="submodule_id">Módulo: </label>
                            <select name="module_id" id="module_id">
                                <option value="">-- Seleccione --</option>
                                @foreach($md as $m)
                                    <option value="{{ $m->id }}">{{ $m->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group module">
                            <label for="submodule_id">Submódulo: </label>
                            <select name="submodule_id" id="submodule_id">
                                <option value="">-- Seleccione --</option>
                                @foreach($sub as $s)
                                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre: </label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="guard_name">Guard Name: </label>
                            <input type="text" class="form-control" id="guard_name" name="guard_name">
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción: </label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <input type="hidden" name="id" id="id">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan
<script src="{{ asset('js/permissions.js') }}"></script>
@endsection
@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Empresa') <!-- Título dinámico -->
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Empresa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('business.enterprise') }}">Negocio</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
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
                    <h3 class="card-title">Datos de empresa</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-sm-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Actualizar información general</a>

                                    <a class="nav-link" id="vert-tabs-representante-tab" data-toggle="pill" href="#vert-tabs-representante" role="tab" aria-controls="vert-tabs-representante" aria-selected="false">Actualizar foto representante legal</a>

                                    <a class="nav-link" id="vert-tabs-logo-tab" data-toggle="pill" href="#vert-tabs-logo" role="tab" aria-controls="vert-tabs-logo" aria-selected="false">Actualizar logo</a>

                                    <a class="nav-link" id="vert-tabs-logo-min-tab" data-toggle="pill" href="#vert-tabs-logo-min" role="tab" aria-controls="vert-tabs-logo-min" aria-selected="false">Actualizar miniatura</a>
                                </div>
                            </div>

                            <div class="col-7 col-sm-9">
                                <div class="tab-content" id="vert-tabs-tabContent">

                                    <div class="tab-pane text-left fade active show" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
                                        <form id="enterpriseForm" method="post">
                                            @csrf
                                            <input type="hidden" name="op" value="1">
                                            <input type="hidden" name="id" id="id" value="{{ $etp->id }}">
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="razon_social">Razón Social:</label>
                                                        <input type="text" class="form-control" id="razon_social" name="razon_social" value="{{ $etp->razon_social }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="nombre_comercial">Nombre Comercial:</label>
                                                        <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" value="{{ $etp->nombre_comercial }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="ruc">RUC:</label>
                                                        <input type="text" class="form-control" id="ruc" name="ruc" value="{{ $etp->ruc }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="email">E-mail:</label>
                                                        <input type="text" class="form-control" id="email" name="email" value="{{ $etp->email_comercial }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="pais">País:</label>
                                                        <input type="text" class="form-control" id="pais" name="pais" value="{{ $etp->pais }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="codigo_pais">Código país:</label>
                                                        <input type="text" class="form-control" id="codigo_pais" name="codigo_pais" value="{{ $etp->codigo_pais }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="telefono">Celular:</label>
                                                        <input type="text" class="form-control" id="telefono" name="telefono" value="{{ $etp->telefono_comercial }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="pagina_web">Pagina web:</label>
                                                        <input type="text" class="form-control" id="pagina_web" name="pagina_web" value="{{ $etp->pagina_web }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="representante_legal">Representante legal:</label>
                                                        <input type="text" class="form-control" id="representante_legal" name="representante_legal" value="{{ $etp->representante_legal }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="ubigeo">Ubigeo:</label>
                                                        <input type="text" class="form-control" id="ubigeo" name="ubigeo" value="{{ $etp->ciudad }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="direccion">Dirección:</label>
                                                        <input type="text" class="form-control" id="direccion" name="direccion" value="{{ $etp->direccion }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="rubro">Rubro:</label>
                                                        <input type="text" class="form-control" id="rubro" name="rubro" value="{{ $etp->rubro_empresa }}">
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="fce">Fecha fundación empresa:</label>
                                                        <input type="date" class="form-control" id="fce" name="fce" value="{{ $etp->fecha_creacion->format('Y-m-d') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-representante" role="tabpanel" aria-labelledby="vert-tabs-logo-tab">
                                        <form id="fotoRepresentanteForm" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="op" value="2">
                                            <input type="hidden" name="id" id="id" value="{{ $etp->id }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="foto-representante">Seleccionar logo:</label>
                                                        <input type="file" class="form-control foto-representante" id="foto-representante" name="foto-representante" accept="image/*">
                                                        <p class="help-block">Peso máximo de la foto 2MB</p>
                                                        <img src="{{ asset('storage/photos/anonymous.png') }}" class="img-thumbnail preview-representante">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-logo" role="tabpanel" aria-labelledby="vert-tabs-logo-tab">
                                        <form id="logoForm" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="op" value="3">
                                            <input type="hidden" name="id" id="id" value="{{ $etp->id }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="logo">Seleccionar logo:</label>
                                                        <input type="file" class="form-control logo" id="logo" name="logo" accept="image/*">
                                                        <p class="help-block">Peso máximo de la foto 2MB</p>
                                                        <img src="{{ asset('storage/img/anonymous.png') }}" class="img-thumbnail preview-logo">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-logo-min" role="tabpanel" aria-labelledby="vert-tabs-logo-min-tab">
                                        <form id="logoMiniForm" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="op" value="4">
                                            <input type="hidden" name="id" id="id" value="{{ $etp->id }}">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="imagen">Seleccionar logo miniatura:</label>
                                                    <input type="file" class="form-control mini-logo" id="mini-logo" name="mini-logo" accept="image/*">
                                                    <p class="help-block">Peso máximo de la foto 2MB</p>
                                                    <img src="{{ asset('storage/img/anonymous.png') }}" class="img-thumbnail preview-mini-logo">
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
                </div>
            </div>
        </div>
    </section>
</div>
<script src="{{ asset('js/enterprise.js') }}"></script>
@endsection

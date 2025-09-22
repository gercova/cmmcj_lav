@extends('layouts.skelenton')
@section('title', config('global.site_name').' - Actualizar Examen') <!-- Título dinámico -->
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Actualizar examen</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('emr.exams.home') }}">Exámenes</a></li>
                        <li class="breadcrumb-item active">Actualizar examen</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                </div>
                <form id="examForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id" value="{{ $exam->id }}">
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
                                        <input type="text" value="" class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Fecha:</label>
                                    <div class="col-sm-8">
                                        <input type="date" class="form-control form-control-sm" name="fechaM">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DNI :: NOMBRES </label>
                                    <input type="text" class="form-control" value="{{ $hc[0]->dni.' :: '.$hc[0]->nombres }}" readonly>
                                    <input type="hidden" name="historia_id" id="historia_id" value="{{ $hc[0]->history }}">
                                    <input type="hidden" name="examId" id="examId" value="{{ $exam->id }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="examen_tipo_id">Tipo consulta</label>
                                    <select class="form-control" name="examen_tipo_id" id="examen_tipo_id" required>
                                        <option value="">-- Seleccione --</option>
                                        @foreach($te as $t)
                                            <option value="{{ $t->id }}" {{ $exam->examen_tipo_id == $t->id ? 'selected' : '' }}>{{ $t->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Funciones vitales</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="pa">P.A</label>
                                                <input type="text" class="form-control form-control-sm" id="pa" name="pa" value="{{ $exam->pa }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="fc">FC</label>
                                                <input type="text" class="form-control form-control-sm" id="fc" name="fc" value="{{ $exam->fc }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="fr">FR</label>
                                                <input type="text" class="form-control form-control-sm" id="fr" name="fr" value="{{ $exam->fr }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="t">T</label>
                                                <input type="text" class="form-control form-control-sm" id="t" name="t" value="{{ $exam->t }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="peso">Peso (kg)</label>
                                                <input type="text" class="form-control form-control-sm" id="peso" name="peso" value="{{ $exam->peso }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="talla">Talla (cm)</label>
                                                <input type="text" class="form-control form-control-sm" id="talla" name="talla" value="{{ $exam->talla }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label for="imc">IMC: </label>
                                                <input type="text" class="form-control form-control-sm" id="imc" name="imc" value="{{ $exam->imc }}" step="0.01" onkeypress="return imc(event);" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Motivo consulta</label>
                                                <input type="text" class="form-control form-control-sm" id="motivo_consulta" name="motivo_consulta" value="{{ $exam->motivo_consulta }}">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Antecedentes fisiológicos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="m">M:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="m" name="m" value="{{ $exam->m }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="rc">RC:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="rc" name="rc" value="{{ $exam->rc }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="g">G:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control form-control-sm date" id="g" name="g" value="{{ $exam->g }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="p">P:</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control form-control-sm date" id="p" name="p" value="{{ $exam->p }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-5 col-form-label" for="u_parto">U. PARTO:&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary1" name="r1" value="V" {{ $exam->r1 == 'V' ? 'checked' : '' }}>
                                                            <label for="radioPrimary1">(V)</label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="radioPrimary2" name="r1" value="C" {{ $exam->r1 == 'C' ? 'checked' : '' }}>
                                                            <label for="radioPrimary2">(C)</label>
                                                        </div>
                                                    </label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control form-control-sm" id="u_parto" name="u_parto" value="{{ $exam->u_parto }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="u-pap">U. PAP:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm date" id="u_pap" name="u_pap" value="{{ $exam->u_pap }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="u_ivaa">U. IVAA:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm date" id="u_ivaa" name="u_ivaa" value="{{ $exam->u_ivaa }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="mac_id">MAC:</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control form-control-sm" name="mac_id" id="mac_id">
                                                            <option value="">-- Seleccione --</option>
                                                            @foreach($mac as $m)
                                                                <option value="{{ $m->id }}" {{ $exam->mac_id == $m->id ? 'selected' : '' }}>{{ $m->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Exámenes físico</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fum">Fecha última regla:</label>
                                                    <input type="text" class="form-control form-control-sm date" onchange="getFpp(this.value);" id="fum" name="fum" value="@if($exam->fum) {{ $exam->fum->format('Y-m-d') }} @else {{ '' }} @endif">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fpp">Fecha estimada del parto:</label>
                                                    <input type="text" class="form-control form-control-sm" id="fpp" name="fpp" value="{{ $exam->fpp }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="eg">Edad gestacional:</label>
                                                    <input type="text" class="form-control form-control-sm" id="eg" name="eg" value="{{ $exam->eg }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="ag">Apreciación general:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="ag" name="ag" rows="1">{{ $exam->ag }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="pym">Piel y mucosas:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="pym" name="pym" rows="1">{{ $exam->pym }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="ap">Aparato respiratorio:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="ap" name="ap" rows="1">{{ $exam->ap }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="cv">Cardio-vascular:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="cv" name="cv" rows="1">{{ $exam->cv }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="abdomen">Abdomen:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="abdomen" name="abdomen" rows="1">{{ $exam->abdomen }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="gu">Génito urinario:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="gu" name="gu" rows="1">{{ $exam->gu }}</textarea>
                                                        <hr>
                                                        <div class="col-md-12 row">
                                                            <div class="col-md-3">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label" for="c_abm">D:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control form-control-sm" id="c_abm" name="c_abm" value="{{ $exam->c_abm }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label" for="i_abm">I:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control form-control-sm" id="i_abm" name="i_abm" value="{{ $exam->i_abm }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label" for="ap_abm">AP:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control form-control-sm" id="ap_abm" name="ap_abm" value="{{ $exam->ap_abm }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label" for="c_abm">C:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control form-control-sm" id="c_abm" name="c_abm" value="{{ $exam->c_abm }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label" for="p_abm">P:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control form-control-sm" id="p_abm" name="p_abm" value="{{ $exam->p_abm }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-2 col-form-label" for="mo_abm">MO:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control form-control-sm" id="mo_abm" name="mo_abm" value="{{ $exam->mo_abm }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group row">
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="radio" id="io_ro1" name="io_ro_abm" value="io" {{ $exam->io_ro_abm == 'io' ? 'checked' : '' }}>
                                                                        <label for="io_ro1">IO</label>
                                                                    </div>
                                                                    &nbsp;&nbsp;&nbsp;
                                                                    <div class="icheck-primary d-inline">
                                                                        <input type="radio" id="io_ro2" name="io_ro_abm" value="ro" {{ $exam->io_ro_abm == 'ro' ? 'checked' : '' }}>
                                                                        <label for="io_ro2">RO</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="neurologico">Neurológico:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="neurologico" name="neurologico" rows="1">{{ $exam->neurologico }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Examen obstétrico</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="au">AU:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="au" name="au" value="{{ $exam->au }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label" for="spp">SPP:</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="spp" name="spp" value="{{ $exam->spp }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="lcf">LCF:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="lcf" name="lcf" value="{{ $exam->lcf }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="du">DU:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="du" name="du" value="{{ $exam->du }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="mf">MF:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="mf" name="mf" value="{{ $exam->mf }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="oh">Otros hallazgos:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="oh" name="oh" rows="2">{{ $exam->oh }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Atención integral</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Psicoprofilaxis:&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="checkboxPrimary1" name="psico1" value="ok" {{ $exam->psico1 == 'ok' ? 'checked' : '' }}>
                                                            <label for="checkboxPrimary1"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="checkboxPrimary2" name="psico2" value="ok" {{ $exam->psico2 == 'ok' ? 'checked' : '' }}>
                                                            <label for="checkboxPrimary2"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline" name="psico3">
                                                            <input type="checkbox" id="checkboxPrimary3" name="psico3" value="ok" {{ $exam->psico3 == 'ok' ? 'checked' : '' }}>
                                                            <label for="checkboxPrimary3"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="checkboxPrimary4" name="psico4" value="ok" {{ $exam->psico4 == 'ok' ? 'checked' : '' }}>
                                                            <label for="checkboxPrimary4"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="checkboxPrimary5" name="psico5" value="ok" {{ $exam->psico5 == 'ok' ? 'checked' : '' }}>
                                                            <label for="checkboxPrimary5"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="checkboxPrimary6" name="psico6" value="ok" {{ $exam->psico6 == 'ok' ? 'checked' : '' }}>
                                                            <label for="checkboxPrimary6"></label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label" for="nutricion">Nutrición:&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="nutricion" name="nutricion" value="ok" {{ $exam->nutricion == 'ok' ? 'checked' : '' }}>
                                                            <label for="nutricion"></label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Psicología:&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="psico1" name="psicol1" value="ok" {{ $exam->psicol1 == 'ok' ? 'checked' : '' }}>
                                                            <label for="psico1"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="psicol2" name="psicol2" value="ok" {{ $exam->psicol2 == 'ok' ? 'checked' : '' }}>
                                                            <label for="psicol2"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="psicol3" name="psicol3" value="ok" {{ $exam->psicol3 == 'ok' ? 'checked' : '' }}>
                                                            <label for="psicol3"></label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="psicol4" name="psicol4" value="ok" {{ $exam->psicol4 == 'ok' ? 'checked' : '' }}>
                                                            <label for="psicol4"></label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Odonto:&nbsp;
                                                        <div class="icheck-primary d-inline">
                                                            <input type="checkbox" id="odontologia" name="odontologia" value="ok" {{ $exam->odontologia == 'ok' ? 'checked' : '' }}>
                                                            <label for="odontologia"></label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">Pezones:&nbsp;
                                                        <img width="50" class="img-fluid img-thumbnail" src="{{ asset('storage/enterprise/p-1.svg') }}">
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="pezon1" name="pezon" value="okp1" {{ $exam->pezon === 'okp1' ? 'checked' : '' }}>
                                                            <label for="pezon1"></label>
                                                        </div>
                                                        <img width="50" class="img-fluid img-thumbnail" src="{{ asset('storage/enterprise/p-2.svg') }}">
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="pezon2" name="pezon" value="okp2" {{ $exam->pezon === 'okp2' ? 'checked' : '' }}>
                                                            <label for="pezon2"></label>
                                                        </div>
                                                        <img width="50" class="img-fluid img-thumbnail" src="{{ asset('storage/enterprise/p-3.svg') }}">
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" id="pezon3" name="pezon" value="okp3" {{ $exam->pezon === 'okp3' ? 'checked' : '' }}>
                                                            <label for="pezon3"></label>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Diagnósticos</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="diagnostics">Buscar:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="diagnostics" placeholder="Buscar por código o nombre">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <button id="btn-add-diagnostic" type="button" class="btn btn-primary btn-block"><i class="bi bi-plus-circle"></i> Agregar diagnóstico</button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <table id="tableDiagnostics" class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:10%;">Código</th>
                                                            <th style="width:90%;">Diagnóstico</th>
                                                            <th style="width:10%;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Tratamiento</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label" for="drugs">Buscar:</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="drugs" placeholder="Buscar por descripción">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <button id="btn-add-drug" type="button" class="btn btn-primary btn-block"><i class="bi bi-plus-circle"></i> Agregar fármaco</button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <table id="tableDrugs" class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:30%;">Fármaco</th>
                                                            <th style="width:45%;">Receta</th>
                                                            <th style="width:25%">Dosis</th>
                                                            <th style="width:10%;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" for="recomendaciones">Recomendaciones:</label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control form-control-sm" id="recomendaciones" name="recomendaciones" rows="2">{{ $exam->recomendaciones }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h5 class="card-title">Exámenes auxiliares</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <button id="btn-add-test" type="button" class="btn btn-primary btn-block"><i class="bi bi-plus-circle"></i> Agregar campo</button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <table id="tabletest" class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Examen</th>
                                                            <th>Fecha</th>
                                                            <th>Resultado</th>
                                                            <th></th>
                                                        </tr>
                                                    <thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script src="{{ asset('js/exams.js') }}"></script>
@endsection

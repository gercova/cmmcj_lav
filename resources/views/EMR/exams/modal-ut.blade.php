<div class="modal fade" id="modalUrineForm" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="examUrineForm" autocomplete="off">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Atendido por:</label>
                                <div class="col-sm-8">
                                    <input type="text" value="{{ auth()->user()->name }}" class="form-control form-control-sm" readonly>
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
                                <input type="text" class="form-control form-control-sm" value="{{ $history->dni.' :: '.$history->nombres }}" readonly>
                                <input type="hidden" name="historia_id" id="historia_id" value="{{ $history->id }}">
                                <input type="hidden" name="examen_id" id="examen_id" value="">
                                <input type="hidden" name="examen_orina_id" id="examen_orina_id" value="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="color">Color:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="color" name="color">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="aspecto">Aspecto:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="aspecto" name="aspecto">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="densidad">Densidad:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="densidad" name="densidad">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="ph">PH:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="ph" name="ph">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="proteinas">Proteinas:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="proteinas" name="proteinas">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="glucosa">Glucosa:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="glucosa" name="glucosa">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="cetonas">Cetonas:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="cetonas" name="cetonas">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="bilirrubina">Bilirrubina:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="bilirrubina" name="bilirrubina">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="sangre_oculta">Sangre Oculta:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="sangre_oculta" name="sangre_oculta">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="urobilinogeno">Urobilinogeno:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="urobilinogeno" name="urobilinogeno">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="nitritos">Nitritos:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="nitritos" name="nitritos">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="leucocitos_quimico">Leucocitos Quimico:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="leucocitos_quimico" name="leucocitos_quimico">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="leucocitos_campo">Leucocitos Campo:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="leucocitos_campo" name="leucocitos_campo">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="hematies_campo">Hematies Campo:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="hematies_campo" name="hematies_campo">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="celulas_epiteliales">Celulas Epiteliales:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="celulas_epiteliales" name="celulas_epiteliales">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="bacterias">Bacterias:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="bacterias" name="bacterias">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="cristales">Cristales:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="cristales" name="cristales">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="cilindros">Cilindros:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="cilindros" name="cilindros">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="mucus">Mucus:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="mucus" name="mucus">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="observaciones">Observaciones:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="observaciones" name="observaciones">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
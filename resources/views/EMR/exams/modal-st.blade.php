<div class="modal fade" id="modalStoolForm" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="examStoolForm" autocomplete="off">
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
                                <input type="hidden" name="examen_heces_id" id="examen_heces_id" value="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="consistencia">Consistencia:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="consistencia" name="consistencia">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="color">Color:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="color" name="color">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="mucus">Mucus:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="mucus" name="mucus">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="restos_alimenticios">Restos Alimenticios:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="restos_alimenticios" name="restos_alimenticios">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="leucocitos">Leucocitos:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="leucocitos" name="leucocitos">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="hematies">Hematies:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="hematies" name="hematies">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="bacterias">Bacterias:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="bacterias" name="bacterias">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="levaduras">Levaduras:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="levaduras" name="levaduras">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="parasitos">Parásitos:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm" id="parasitos" name="parasitos">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="huevos_parasitos">Huevos de parásitos:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="huevos_parasitos" name="huevos_parasitos">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label" for="sangre_oculta">Sangre oculta:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" id="sangre_oculta" name="sangre_oculta">
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
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="grasa_fecal">Grasa fecal:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="grasa_fecal" name="grasa_fecal">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="cultivo_bacteriano">Cultivo bacteriano:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="cultivo_bacteriano" name="cultivo_bacteriano">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="sensibilidad_antimicrobiana">Sensibilidad antimicrobiana:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="sensibilidad_antimicrobiana" name="sensibilidad_antimicrobiana">
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
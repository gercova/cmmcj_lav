<div class="modal fade" id="modalBloodForm" tabindex="-1" aria-modal="true" role="dialog" data-backdrop="static" aria-labelledby="staticBackdropLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="examBloodForm" autocomplete="off">
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
                                <input type="hidden" name="examen_sangre_id" id="examen_sangre_id" value="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hemoglobina">Hemoglobina:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="hemoglobina" name="hemoglobina">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hematocrito">Hematocrito:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="hematocrito" name="hematocrito">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="leucocitos">Leucocitos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="leucocitos" name="leucocitos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="neutrofilos">Neutrofilos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="neutrofilos" name="neutrofilos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="linfocitos">Linfocitos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="linfocitos" name="linfocitos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="monocitos">Monocitos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="monocitos" name="monocitos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="eosinofilos">Eosinofilos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="eosinofilos" name="eosinofilos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="basofilos">Basofilos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="basofilos" name="basofilos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="plaquetas">Plaquetas:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="plaquetas" name="plaquetas">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="glucosa">Glucosa:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="glucosa" name="glucosa">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="urea">Urea:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="urea" name="urea">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="creatinina">Creatinina:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="creatinina" name="creatinina">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="acido_urico">Acido úrico:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="acido_urico" name="acido_urico">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="colesterol_total">Colesterol total:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="colesterol_total" name="colesterol_total">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="trigliceridos">Triglicéridos:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="trigliceridos" name="trigliceridos">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transaminasas_got">Transaminasas GOT:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="transaminasas_got" name="transaminasas_got">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transaminasas_gpt">Transaminasas GPT:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="transaminasas_gpt" name="transaminasas_gpt">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bilirrubina_total">Bilirrubina total:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="bilirrubina_total" name="bilirrubina_total">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bilirrubina_directa">Bilirrubina directa:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="bilirrubina_directa" name="bilirrubina_directa">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fosfatasa_alcalina">Fosfatasa alcalina:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="fosfatasa_alcalina" name="fosfatasa_alcalina">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="proteinas_totales">Proteínas totales:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="proteinas_totales" name="proteinas_totales">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="albumina">Albumina:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="albumina" name="albumina">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="globulina">Globulina:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="globulina" name="globulina">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sodio">Sodio:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="sodio" name="sodio">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="potasio">Potasio:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="potasio" name="potasio">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cloro">Cloro:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="cloro" name="cloro">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="calcio">Calcio:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="calcio" name="calcio">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="vsg">VSG:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="vsg" name="vsg">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tiempo_protrombina">Tiempo protrombina:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="tiempo_protrombina" name="tiempo_protrombina">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="tpt">Tpt:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="tpt" name="tpt">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="observaciones">Observaciones:</label>
                                <input type="text" class="form-control form-control-sm" id="observaciones" name="observaciones">
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
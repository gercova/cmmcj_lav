$(document).ready(function(){
    const testId = $('#testId').val();
    //Listado de diagnosticos por examen
    manegeDiagnosisTable = $("#tbdiagnosishc").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/listDiagnosisHc/${testId}`,
        'order': [1, 'asc']
    });
    //Receta por examen
    manegeMedicationTable = $("#recipeshc").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/listMedicationHc/${testId}`,
        'order': [1, 'asc']
    });
    //datepicker ingresar fecha manualmente
    $( function() {
        $( ".fecha" ).datepicker();
        $( ".fecha" ).datepicker("option", "dateFormat", 'yy-mm-dd');
    });
    //datepicker
    $('#fechanac').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:' + anio,
        dateFormat: 'yy-mm-dd'
    });
    //validar dni
    /*$("#dni").keyup(function() {
        let dni = $(this).val();
        var regex = /^\d{8}$/;
        if (!regex.test(dni)) {
            swal.fire({
                icon: 'error',
                title: 'Ingrese un número de 8 dígitos',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
            });
        }
    });*/
    //boton extranjero
    $('.extra').click(function(e){
        e.preventDefault();
        $('.extra').addClass('d-none');
        $('.nacional').addClass('d-none');
        $('.pe').removeClass('d-none');
        $('.foreign').removeClass('d-none');
    });
    //paciente nacional
    $('.pe').click(function(){
        $('.pe').addClass('d-none');
        $('.foreign').addClass('d-none');
        $('.nacional').removeClass('d-none');
        $('.extra').removeClass('d-none');
    });
    //tipear número DNI
    $('#dni').keyup(function(){
        var dni = $(this).val();
        if(dni.length == 8){
            consultaDatosSUNAT(dni);
        }
    });
    //calcular imc
    $('#peso, #talla').on('input', function() {
        let peso = parseFloat($('#peso').val());
        let altura = parseFloat($('#talla').val());
        if (!isNaN(peso) && !isNaN(altura) && altura > 0) {
            let imc = peso / (altura * altura);
            $('#imc').val(imc.toFixed(2));
        }
    });
    //tabla index historias
    $('#historias').jtable({
        title       : "HISTORIAS CLÍNICAS",
        paging      : true,
        overflow    : scroll,
        sorting     : true,
        actions: {
            listAction: `${_baseurl_ + _routecontroller_}/list`,
        },
        toolbar: {
            items: [{
                cssClass: 'buscador',
                text: buscador
            }]
        },
        fields: {
            fecha: {
                key: false,
                title: 'FECHA',
                width: '6%' ,
            },
            nro_historia: {
                title: 'HISTORIA',
                width: '8%',
            },
            dni: {
                key: true,
                title: 'DNI',
                width: '5%',
            },
            nombres: {
                title: 'NOMBRES',
                width: '20%',
            },
            fecha_nacimiento: {
                title: 'F.N',
                width: '6%',
            },
            edad: {
                title: 'EDAD',
                width: '4%',
            },
            sexo: {
                title: 'SEXO',
                width: '4%' ,
            },
            opciones:{
                title: 'OPCIONES',
                width: '15%',
                sorting:false,
                edit:false,
                create:false,
                display: (data) => {
                    return (permissions.insert == 1 ? `<button type="button" class="btn btn-info add-quote btn-xs" value="${data.record.id}"><i class="bi bi-card-list"></i></i> Atender</button>` : ``)+'&nbsp;'+(permissions.view == 1 ? `<button type="button" class="btn btn-warning edit-row btn-xs" value="${data.record.id}"><i class="bi bi-pencil-square"></i> Editar</button>` : ``)+'&nbsp;'+(permissions.delete == 1 ? `<button type="button" class="btn btn-danger delete-row btn-xs" data-id="${data.record.id}" value="${data.record.id}"><i class="bi bi-trash"></i> Eliminar</button>` : ``);
                }
            }
        },
        recordsLoaded: (event, data) => {
            if(permissions.insert == 1){
                $('.add-quote').click(function(e){
                    e.preventDefault();
                    let id = $(this).attr('value');
                    Swal.fire({
                        title: '¿Estás seguro de añadir este paciente a la cola de citas?',
                        text: 'Añadir paciente a la agenda de hoy',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, añadir',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.value) {
                            fetch(`${_baseurl_ + _routecontroller_}/quotes/${id}`)
                            .then(res=>res.json())
                            .then(res => {
                                console.log(res)
                                if(res.success == true){
                                    Swal.fire(
                                        '¡Añadido!',
                                        res.messages,
                                        'success'
                                    )
                                }else{
                                    Swal.fire(
                                        '¡Upsss!',
                                        res.messages,
                                        'error'
                                    )
                                }
                            }).catch(function(err) {
                                console.log(err);
                            });
                        }
                    })
                });
            }
            if(permissions.update == 1){
                $('.edit-row').click(function(e){
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = `${_baseurl_ + _routecontroller_}/edit/${id}`;
                });
            }
            if(permissions.delete == 1){
                $('.delete-row').click(function(e) {
                    e.preventDefault();
                    let id = $(this).attr('value');
                    Swal.fire({
                        title: '¿Estás seguro de hacerlo?',
                        text: 'Si borras la historia clínica todos los datos de este paciente serán borrados',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, borrarlo',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.value) {
                            fetch(`${_baseurl_ + _routecontroller_}/delete/${id}`, {
                                method: 'DELETE',
                            })
                            .then(res=>res.json())
                            .then(res => {
                                if(res.success == true){
                                    Swal.fire({
                                        title: 'Cargando...',
                                        html: "Borrando historia clínica y demás registros... <b></b> milisegundos.",
                                        timer: 5000,  // 5000 ms = 5 segundos
                                        timerProgressBar: true,  // Muestra la barra de progreso
                                        didOpen: () => {
                                            Swal.showLoading();
                                            const timer = Swal.getPopup().querySelector("b");
                                                timerInterval = setInterval(() => {
                                                timer.textContent = `${Swal.getTimerLeft()}`;
                                            }, 100);
                                        },
                                        willClose: () => {
                                            clearInterval(timerInterval);
                                            console.log('Alerta cerrada automáticamente después de 8 segundos.');
                                        }
                                    }).then((result) => {
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            console.log(res);
                                            Swal.fire(
                                                '¡Borrado!',
                                                res.messages,
                                                'success'
                                            )
                                            LoadRecordsButton.click();
                                        }
                                    });
                                }
                            }).catch(function(err) {
                                console.log(err);
                            });
                        }
                    })
                });
            }
        }
    });
    LoadRecordsButton = $('#LoadRecordsButton');
    LoadRecordsButton.click(function (e) {
        e.preventDefault();
        //console.log($('#search').val())
        $('#historias').jtable('load', {
            search: $('#search').val()
        });
    });
    LoadRecordsButton.click();
    //buscar ubigeo residencia
    $('.buscarUbigeoR').select2({
        placeholder: "Buscar ubigeo residencia",
        minimumInputLength: 3,
        ajax: {
            type: 'POST',
            url: `${_baseurl_ + _routecontroller_}/buscar_ubigeo`,
            dataType: 'json', 
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            if (data.loading) {
                return data.text;
            }
            var markup = "<option value='" + data.id + "'>" + data.ubigeo + "</option>";
            return markup;
        },
        templateSelection: function(data) {
            return data.ubigeo || data.id;
        },
    });
    // buscar ocupacion
    $('.buscarOcupacion').select2({
        placeholder: "Buscar ocupacion",
        minimumInputLength: 3,
        ajax: {
            type: 'POST',
            url: `${_baseurl_ + _routecontroller_}/buscar_ocupacion`,
            dataType: 'json', 
            delay: 300,
            data: function(params) {
                return {
                    q: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            if (data.loading) {
                return data.text;
            }
            var markup = "<option value='" + data.id + "'>" + data.ocupacion + "</option>";
            return markup;
        },
        templateSelection: function(data) {
            return data.ocupacion || data.id;
        },
    });
    //formulario historia clinica
    $('#formHC').submit(function(e){
        e.preventDefault();
        let dni         = $('#dni').val();
        let nombres     = $('#nombres').val();
        let fechanac    = $('#fechanac').val();
        let sexo        = $('#sexo').val();
        let gs          = $('#gs').val();
        let gi          = $('#gi').val();
        let celular     = $('#celular').val();
        let ec          = $('#ec').val();
        let direccion   = $('#direccion').val();
        
        if(dni && nombres && fechanac && sexo && gs && gi && celular && ec && direccion){
            fetch(`${_baseurl_ + _routecontroller_}/store`, {
                method: 'POST',
                body: new FormData(this),
            })
            .then(res => res.json())
            .then(res => {
                console.log(res);
                if(res.success == true){
                    $('#formHC').trigger('reset');
                    swal.fire({
                        icon: 'success',
                        title: res.messages,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar',
                    }).then((result)=>{
                        if(result.value){
                            window.location.href = `${_baseurl_ + _routecontroller_}`; 
                        }
                    });
                    $('input[name="csrf_token"]').val(res.token);
                }else if(res.success == false){
                    swal.fire({
                        icon: 'error',
                        title: res.messages,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar',
                    });
                    $('input[name="csrf_token"]').val(res.token);
                }
            }).catch(function(err) {
                console.log(err);
            });
            return false;
        }
    });
});
//Función calular edad
function getAge(dateString){
    var today       = new Date(); 
    var birthDate   = new Date(dateString);
    var age         = today.getFullYear() - birthDate.getFullYear();
    var m           = today.getMonth() - birthDate.getMonth();
    if(m < 0 || (m === 0 && today.getDate() < birthDate.getDate())){age--;}
    $("#edad").val(age);
}
//función encontrar nombres por DNI
function consultaDatosSUNAT(dni){
    const url = `${_baseurl_ + _routecontroller_}/consultar_dni`;
    let data = {
        'dni': dni,
        'token': _token_
    };

    const formData = new FormData();
    formData.append('dni', data.dni);
    formData.append('token', data.token);
    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if(data){
            $('#dni').closest('.col-lg-2').removeClass('has-error');
            $('#dni').closest('.col-lg-2').addClass('has-success');
            $('#nombres').closest('.col-lg-6').addClass('has-success');
            $('#dni').val(data.numeroDocumento);
            $('#nombres').val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);
        }else{
            $("#dni").val('');
            $("#nombres").val('');
        }
    }).catch(error => console.error(error));
}
//ingresar solo numeros
function solo_numeros(e){
	let keynum = window.event ? window.event.keyCode : e.which;
	if((keynum == 8)){return true;}
	return /\d/.test(String.fromCharCode(keynum));
}
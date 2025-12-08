$(document).ready(function(){
    let anio = new Date().getFullYear();

    $('#dni').change(function(){
        let doc_type    = $('#tipo_documento_id').val();
        let dni         = $(this).val();
        if (doc_type == ''){
            Swal.fire('Upsss!!!', 'Seleccione el Tipo de documento', 'warning')
            $('#tipo_documento_id').addClass('is-invalid');
        } else if (doc_type == 1) {
            if(dni.length == 8){
                consultaDatosSUNAT(dni);
                $('#tipo_documento_id').removeClass('is-invalid').addClass('is-valid');
            }
        } else if (doc_type == 3) {
            if(dni.length == 9){
                $('#tipo_documento_id').removeClass('is-invalid is-valid');
            }
        } else if (doc_type == 4) {
            $('#tipo_documento_id').removeClass('is-invalid is-valid');
        }
    });
    //datepicker
    $('#fecha_nacimiento').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:' + anio,
        dateFormat: 'yy-mm-dd'
    });

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
    //tabla index historias
    $('#histories').jtable({
        title       : "HISTORIAS CLÍNICAS",
        paging      : true,
        overflow    : scroll,
        sorting     : true,
        actions: {
            listAction: `${API_URL}/sys/histories/list`,
        },
        toolbar: {
            items: [{
                cssClass: 'buscador',
                text: buscador
            }]
        },
        fields: {
            created_at: {
                key: false,
                title: 'FECHA',
                width: '6%' ,
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
                    const permissions = data.record.Permissions || {}; // Obtenemos los permisos del registro
                    let buttons = '';
                    if (permissions.add_appx) {
                        buttons += `
                            <button type="button" class="btn btn-info add-quote btn-xs" value="${data.record.id}">
                                <i class="bi bi-card-list"></i> Atender
                            </button>&nbsp;
                        `;
                    }
                    if (permissions.update_hc) {
                        buttons += `
                            <button type="button" class="btn btn-warning edit-row btn-xs" value="${data.record.id}">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>&nbsp;
                        `;
                    }
                    if (permissions.delete_hc) {
                        buttons += `
                            <button type="button" class="btn btn-danger delete-row btn-xs" data-id="${data.record.id}" value="${data.record.id}">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        `;
                    }

                    return buttons;
                }
            }
        },
        recordsLoaded: (event, data) => {

            $('.add-quote').click(async function(e){
                e.preventDefault();
                const id = $(this).attr('value');
                const response = await axios.get(`${API_URL}/sys/histories/show/${id}`);
                if(response.status == 200) {
                    const history = response.data;
                    $('.modal-title').text('Agendar Cita');
                    $(".text-danger").remove();
                    $('.form-group').removeClass('is-invalid is-valid');
                    $("#paciente").val(history.dni + ' :: ' + history.nombres);
                    $("#historia_id").val(history.id);
                    $("#cita_id").val(null);
                    $('#appointmenDefaultModal').modal('show');
                }
            });

            $('.edit-row').click(function(e){
                e.preventDefault();
                let id = $(this).attr('value');
                window.location.href = `${API_URL}/sys/histories/edit/${id}`;
            });

            $('.delete-row').click(async function(e) {
                e.preventDefault();
                const id = $(this).attr('value');
                try {
                    const result = await swal.fire({
                        title: '¿Estás seguro de hacerlo?',
                        text: 'Si borras la historia clínica todos los datos de este paciente serán borrados',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, borrarlo',
                        cancelButtonText: 'Cancelar',
                    });
                    if (result.isConfirmed) {
                        const response = await axios.delete(`${API_URL}/sys/histories/${id}`);
                        if(response.status == 200 && response.data.status == true){
                            Swal.fire({
                                title: 'Cargando...',
                                html: "Borrando historia clínica y demás registros... <b></b> milisegundos.",
                                timer: 8000,  // 8000 ms = 8 segundos
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
                                    //console.log(response);
                                    Swal.fire('Operación exitosa', response.data.message, response.data.type)
                                    LoadRecordsButton.click();
                                }
                            });
                        }else{
                            Swal.fire('Operación fallida', response.data.message, response.data.type)
                        }
                    }
                } catch (error) {
                    console.error(error);
                }
            });

        }
    });
    LoadRecordsButton = $('#LoadRecordsButton');
    LoadRecordsButton.click(function (e) {
        e.preventDefault();
        $('#histories').jtable('load', {
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
            url: `${API_URL}/sys/histories/location`,
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
            url: `${API_URL}/sys/histories/occupation`,
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
    $('#historyForm').submit(async function(e){
        e.preventDefault();

        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
            const response = await axios.post(`${API_URL}/sys/histories/store`, formData);
            if(response.status == 200 && response.data.status == true){
                $('.text-danger').remove();
                $('.form-group').removeClass('is-invalid is-valid');
                Swal.fire(
                    'Operación exitosa',
                    response.data.message,
                    response.data.type
                ).then((result)=>{
                    if(result.value){
                        window.location.href = response.data.route;
                    }
                });
            }else if(response.data.status == false){
                swal.fire({
                    icon: 'error',
                    title: response.data.message,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar'
                });
            }
        } catch (error) {
            if(error.response && error.response.data.errors){
                $.each(error.response.data.errors, function(key, value) {
                    let inputElement = $(document).find('[name="' + key + '"]');
                    inputElement.after('<span class="text-danger">' + value[0] + '</span>').closest('.form-control').addClass('is-invalid').focus();
                });
            }
        } finally {
            submitButton.prop('disabled', false).html(originalButtonText);
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
//funcion encontrar nombres por DNI
async function consultaDatosSUNAT(dni){
    let data = {
        dni: dni
    };
    const formData = new FormData();
    formData.append('dni', data.dni);
    try {
        const response = await axios.post(`${API_URL}/sys/histories/dni`, formData);
        if(response.status == 200){
            $('#dni').closest('.col-2').removeClass('is-invalid');
            $('#dni').closest('.col-2').addClass('is-valid');
            $('#nombres').closest('.col-6').addClass('is-valid');
            $('#dni').val(response.data.document_number);
            $('#nombres').val(response.data.first_name + ' ' + response.data.first_last_name + ' ' + response.data.second_last_name);
        }else{
            $("#dni").val(dni);
            //$("#nombres").val('');
        }
    } catch (error) {
        console.error(error);
    }
}

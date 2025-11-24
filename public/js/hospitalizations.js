$('#histories').jtable({
    title       : "HOSPITALIZACIONES",
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
            width: '6%' ,

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
        ver:{
            title: 'OPCIONES',
            width: '10%',
            sorting:false,
            edit:false,
            create:false,
            display: (data) => {
                const permissions = data.record.Permissions || {}; // Obtenemos los permisos del registro
                let buttons = '';
                if (permissions.view_hsp) {
                    buttons += `
                        <button type="button" class="btn btn-info view-row btn-xs" value="${data.record.id}">
                            <i class="bi bi-folder"></i> Ver
                        </button>&nbsp;
                    `;
                }
                if (permissions.add_hsp) {
                    buttons += `
                        <button type="button" class="btn btn-success add-new btn-xs" value="${data.record.id}">
                            <i class="bi bi-plus-square-fill"></i> Nuevo
                        </button>&nbsp;
                    `;
                }
                
                return buttons;
            }
        },
    },
    recordsLoaded: (event, data) => {
        $('.add-new').click(function(e){
            e.preventDefault();
            let id = $(this).attr('value');
            window.location.href = `${API_URL}/sys/hospitalizations/new/${id}`;
        });
        
        $('.view-row').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('value');
            window.location.href = `${API_URL}/sys/hospitalizations/see/${id}`;
        });
    }
});

LoadRecordsButton = $('#LoadRecordsButton');
LoadRecordsButton.click(function (e) {
    e.preventDefault();
    console.log($('#search').val())
    $('#histories').jtable('load', {
        search: $('#search').val()
    });
});
LoadRecordsButton.click();

const historyId = $('#historyId').val();

const tables = {
    //listado de hospitalizaciones por historia clínica
    hospitalizations: $('#hospitalization_data').DataTable({ ajax: `${API_URL}/sys/hospitalizations/list/${historyId}`, processing: true }),
};

//Eliminar un registro
DeleteHandler.initButtons([
    {
        selector: '.delete-hospitalization',
        endpoint: 'hsp',
        table: tables.hospitalizations
    }
]);

//datepicker
$('.date').datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '1920:' + anio,
    dateFormat: 'yy-mm-dd'
});

//Funcion para validar datos antes de ser enviados al controlador para guardar o actualizar un examen
$('#hospitalizationForm').submit(async function(e){
    e.preventDefault();
    $('.text-danger').remove();
    $('.form-group').removeClass('is-invalid is-valid');
    const formData              = new FormData(this);
    const submitButton          = $(this).find('button[type="submit"]');
    const originalButtonText    = submitButton.html();
    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
    try {
        const response = await axios.post(`${API_URL}/sys/hospitalizations/store`, formData);
        // Validar que la respuesta sea exitosa y tenga los datos esperados
        if (response.status === 200 && response.data && response.data.status === true) {
            console.log(response.data);
            // Limpiar el formulario y mensajes de error
            $('#hospitalizationForm').trigger('reset');
            $('#hospitalizationForm').find('.text-danger').remove();
            $('.form-control').removeClass('is-invalid is-valid');
            // Mostrar un mensaje de carga mientras se suben los archivos
            const result = await Swal.fire({
                title: 'Guardando información',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getHtmlContainer().querySelector('b');
                    timerInterval = setInterval(() => {
                        timer.textContent = Swal.getTimerLeft();
                    }, 100);
                },
                willClose: () => { clearInterval(timerInterval); }
            });
            // Si el temporizador termina, mostrar un mensaje de éxito
            if (result.dismiss === Swal.DismissReason.timer) {
                await Swal.fire({
                    icon: response.data.type || 'success', // Usar 'success' como valor predeterminado
                    title: response.data.message || 'Información guardada correctamente',
                    html: `<div class="text-center">
                        <p class="mb-3">¿Deseas imprimir el informe?</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a class="btn btn-outline-info d-flex flex-column align-items-center p-3" href="${response.data.route_print}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                <span>Formato A4</span>
                                <small class="badge badge-light mt-1">Carta</small>
                            </a>
                        </div>
                    </div>`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar',
                });
                // Redirigir si hay una ruta definida
                if (response.data.route) {
                    window.location.href = response.data.route;
                }
            }
        } else if (response.data && response.data.status === false) {
            // Mostrar un mensaje de error si el servidor indica un fallo
            await Swal.fire({
                icon: response.data.type || 'error',
                title: response.data.messages || 'Error al guardar la información',
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000
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
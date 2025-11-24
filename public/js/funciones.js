$(document).ready(function() {
    function cargarEstados() {
        axios.get('/sys/appointments/listStatus').then(function(response) {
            const results = response.data;
            const select = $('#estado_cita_id');
            select.empty().append('<option value="">-- Selecciona --</option>');
            results.forEach(function(res) {
                select.append(
                    `<option value="${res.id}">${res.descripcion}</option>`
                );
            });
        })
        .catch(function(error) {
            console.error('Error cargando los estados:', error);
            alert('Error al cargar los estados');
        });
    }

    cargarEstados();

    moment.locale('es');

    // Inicializar DatePicker para FECHA
    $('#datepickerFecha').datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        },
        buttons: {
            showToday: true,
            showClear: true,
            showClose: true
        }
    });

    // Inicializar DatePicker para HORA
    $('#datepickerHora').datetimepicker({
        format: 'HH:mm',
        locale: 'es',
        stepping: 15, // Incrementos de 15 minutos
        enabledHours: [7, 8, 9, 10, 11, 15, 16, 17, 18], // Solo horas permitidas
        disabledTimeIntervals: [
            [moment({ h: 0 }), moment({ h: 7 })],    // Deshabilitar 12am - 7am
            [moment({ h: 12 }), moment({ h: 15 })],  // Deshabilitar 12pm - 3pm
            [moment({ h: 19 }), moment({ h: 24 })],  // Deshabilitar 7pm - 12am
        ],
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        },
        buttons: {
            showClear: true,
            showClose: true
        }
    });

    $('#appointmentForm').submit(async function(e){
        e.preventDefault();
        
        // Limpiar errores previos
        $('.form-control').removeClass('is-invalid is-valid');
        $('.invalid-feedback').remove(); // Eliminar mensajes de error previos
        $('.text-danger').remove(); // Por si había alguno con clase diferente
        
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
            const response = await axios.post(`${API_URL}/sys/appointments/store`, formData);
            
            if(response.status == 200 && response.data.status == true){
                console.log(response);
                $('#appointmentForm').trigger('reset');
                $('#appointmentModal').modal('hide');
                $('#quotes_data').DataTable().ajax.reload();
                alertNotify(response.data.type, response.data.message);
            } else if(response.data.status == false){
                alertNotify(response.data.type, response.data.message);
            }
            
        } catch(error) {
            if(error.response && error.response.data.errors){
                $.each(error.response.data.errors, function(key, value) {
                    // Buscar el input por su atributo name
                    let inputElement = $('[name="' + key + '"]');
                    
                    if(inputElement.length > 0){
                        // Agregar clase is-invalid al input
                        inputElement.addClass('is-invalid');
                        
                        // Buscar el form-group padre
                        let formGroup = inputElement.closest('.form-group');
                        
                        // Crear el mensaje de error con la clase de Bootstrap
                        let errorMessage = '<div class="invalid-feedback d-block">' + value[0] + '</div>';
                        
                        // Agregar el mensaje al final del form-group
                        formGroup.append(errorMessage);
                        
                        // Hacer scroll al primer error (opcional)
                        if(formGroup.is(':first-child') || $('.invalid-feedback').length === 1){
                            $('html, body').animate({
                                scrollTop: formGroup.offset().top - 100
                            }, 500);
                        }
                    }
                });
                
                // Mostrar notificación general (opcional)
                alertNotify('error', 'Por favor corrige los errores en el formulario.');
            } else {
                // Error genérico
                alertNotify('error', 'Ocurrió un error al procesar la solicitud.');
            }
        } finally {
            submitButton.prop('disabled', false).html(originalButtonText);
        }
    });

    // Limpiar error cuando el usuario empiece a escribir
    $(document).on('input change', '.form-control.is-invalid', function(){
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('.invalid-feedback').remove();
    });
});

function alertNotify(icon, messages){
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })  
    Toast.fire({
        icon: icon,
        title: messages,
    })
}
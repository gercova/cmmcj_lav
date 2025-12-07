const historyId = $('#historyId').val();
const examId    = $('#examId').val();

const tables = {
    //listado de exámenes por historia clínica
    exams: $('#exam_data').DataTable({ ajax: `${API_URL}/sys/exams/list/${historyId}`, processing: true, autoWidth: false, responsive: true }),
    bloodTest: $('#bloodtest_data').DataTable({ ajax: `${API_URL}/sys/exams/list-bt/${historyId}`, processing: true, autoWidth: false, responsive: true}),
    urineTest: $('#urinetest_data').DataTable({ ajax: `${API_URL}/sys/exams/list-ut/${historyId}`, processing: true, autoWidth: false, responsive: true}),
    stoolTest: $('#stooltest_data').DataTable({ ajax: `${API_URL}/sys/exams/list-st/${historyId}`, processing: true, autoWidth: false, responsive: true}),
};

let tables2 = '';
if(examId){
    tables2 = {
        //Listado de diagnosticos por examen
        diagnosticId: $('#tableDiagnostics').DataTable({ ajax: `${API_URL}/sys/exams/list-dx/${examId}`, searching:false, bLengthChange:false, processing:true, order:[]}),
        //Receta por examen
        examId: $('#tableDrugs').DataTable({ ajax: `${API_URL}/sys/exams/list-mx/${examId}`, searching:false, bLengthChange:false, processing:true, order:[]}),
        //Tabla de pdf
        documentId: $('#document_data').DataTable({ ajax: `${API_URL}/sys/exams/list-dc/${examId}`, searching:false, bLengthChange:false, processing:true, order:[]}),
    }
}

//Eliminar un registro
DeleteHandler.initButtons([
    { selector: '.delete-exam', endpoint: 'ex', table: tables.exams },
    { selector: '.delete-bt', endpoint: 'ex-bt', table: tables.bloodTest },
    { selector: '.delete-ut', endpoint: 'ex-ut', table: tables.urineTest },
    { selector: '.delete-st', endpoint: 'ex-st', table: tables.stoolTest },
    { selector: '.delete-diagnostic', endpoint: 'ex-dx', table: tables2.diagnosticId },
    { selector: '.delete-drug', endpoint: 'ex-mx', table: tables2.examId },
    { selector: '.delete-image', endpoint: 'ex-img', table: tables2.documentId }
]);

//remover modulos filas de la tabla
$(document).on('click','.btn-remove-img', function(){
    $(this).closest('tr').remove();
});

//tabla index examenes
$('#histories').jtable({
    title       : "EXÁMENES CLÍNICOS",
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
                if (permissions.view_exm) {
                    buttons += `
                        <button type="button" class="btn btn-info view-row btn-xs" value="${data.record.id}">
                            <i class="bi bi-folder"></i> Ver
                        </button>&nbsp;
                    `;
                }
                if (permissions.add_exm) {
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
            window.location.href = `${API_URL}/sys/exams/new/${id}`;
        });

        $('.view-row').click(function(e) {
            e.preventDefault();
            let id = $(this).attr('value');
            window.location.href = `${API_URL}/sys/exams/see/${id}`;
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

//calcular imc
$('#peso, #talla').on('input', function() {
    let peso = parseFloat($('#peso').val());
    let altura = parseFloat($('#talla').val());
    if (!isNaN(peso) && !isNaN(altura) && altura > 0) {
        let imc = peso / (altura * altura);
        $('#imc').val(imc.toFixed(2));
    }
});

//Funcion para validar datos antes de ser enviados al controlador para guardar o actualizar un examen
$('#examForm').submit(async function(e){
    e.preventDefault();
    $('.text-danger').remove();
    $('.form-group').removeClass('is-invalid is-valid');
    const formData              = new FormData(this);
    const submitButton          = $(this).find('button[type="submit"]');
    const originalButtonText    = submitButton.html();
    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
    try {
        const response = await axios.post(`${API_URL}/sys/exams/store`, formData);
        // Validar que la respuesta sea exitosa y tenga los datos esperados
        if (response.status === 200 && response.data && response.data.status === true) {
            console.log(response.data);
            // Limpiar el formulario y mensajes de error
            $('#examForm').trigger('reset');
            $('#examForm').find('.text-danger').remove();
            $('.form-control').removeClass('is-invalid is-valid');
            // Mostrar un mensaje de carga mientras se suben los archivos
            const result = await Swal.fire({
                title: 'Subiendo archivos y guardando información',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timer: 4000,
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
                        <p class="mb-3">Selecciona el formato de impresión:</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a class="btn btn-outline-info d-flex flex-column align-items-center p-3" href="${response.data.print_a4}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                <span>Formato A4</span>
                                <small class="badge badge-light mt-1">Carta</small>
                            </a>
                            &nbsp;
                            <a class="btn btn-outline-success d-flex flex-column align-items-center p-3" href="${response.data.print_a5}" target="_blank">
                                <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                <span>Formato A5</span>
                                <small class="badge badge-light mt-1">Medio</small>
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

//Función para buscar  un diagnóstico
$('#diagnostics').autocomplete({
    source: async function(request, response){
        try {
            // Realizar la solicitud con Axios
            const result = await axios.post(`${API_URL}/sys/diagnostics/search`, {
                q: request.term // Término de búsqueda
            });
            // Procesar la respuesta y pasar los datos al autocomplete
            response(result.data.data);
        } catch (error) {
            console.error('Error en la búsqueda:', error);
            response([]); // Enviar un array vacío en caso de error
        }
    },
    minLength: 2,
    select: function(event, ui){
        data = `${ui.item.id}*${ui.item.cod}*${ui.item.label}`;
        $('#btn-add-diagnostic').val(data);
    },
});

//Funciones para agregar diagnostico
$('#btn-add-diagnostic').on('click', async function(){
    const data = $(this).val();
    if(!data) {
        Swal.fire('¡Vacío!', 'Escribe algo', 'error');
        return;
    }

    const [id, code, name] = data.split('*');
    const formattedName = name?.includes(' - ') ? name.split(' - ')[1] : name;

    const dataMatch = {
        examId: examId,
        diagnosticId: id,
    }

    try {
        const validateMatch = await axios.post(`${API_URL}/sys/ex-dx/validate-match`, dataMatch);
        if (validateMatch.status === 200 && validateMatch.data.status === true) {
            Swal.fire({
                title: '¡Duplicado!',
                text: validateMatch.data.message,
                icon: 'warning',
                confirmButtonText: 'Aceptar'
            });

            $('#diagnostics, #btn-add-diagnostic').val(null);
            return;
        }
    } catch (error) {
        Swal.fire('Error','Ocurrió un error al validar la coincidencia. Intente nuevamente.', 'error');
    }

    if ($(`input[value="${id}"]`).length) {
        Swal.fire('¡Duplicado!', 'El diagnóstico ya está en la lista.', 'warning');
        $('#diagnostics, #btn-add-diagnostic').val(null);
        return;
    }

    $('#tableDiagnostics tbody').append(`
        <tr>
            <td><input type="hidden" name="diagnostic_id[]" value="${id}">${code}</td>
            <td>${formattedName}</td>
            <td><button type="button" class="btn btn-danger btn-xs btn-remove-diagnosis" value="${id}">
                <i class="bi bi-trash"></i></button>
            </td>
        </tr>
    `);

    $('#diagnostics, #btn-add-diagnostic').val(null);
    alertNotify('success', `<h5><b>${formattedName}</b> agregado</h5>`);
});

//Funciones para agregar diagnostico
$('#drugs').autocomplete({
    source: async function (request, response) {
        try {
            // Realizar la solicitud con Axios
            const result = await axios.post(`${API_URL}/sys/drugs/search`, {
                q: request.term // Término de búsqueda
            });
            // Procesar la respuesta y pasar los datos al autocomplete
            response(result.data.data);
        } catch (error) {
            console.error('Error en la búsqueda:', error);
            response([]); // Enviar un array vacío en caso de error
        }
    },
    minLength: 2, // Mínimo de caracteres para iniciar la búsqueda
    select: function (event, ui) {
        // Lógica cuando se selecciona un elemento
        const data = `${ui.item.id}*${ui.item.label}`;
        $('#btn-add-drug').val(data);
    }
});

//Función para agregar el diagnóstico a la lista
$('#btn-add-drug').on('click', async function(){
    data = $(this).val();
    if(data){
        const drug = data.split('*');
        const drugId = drug[0];
        const drugName = drug[1];

        const dataMatch = {
            examId: examId,
            drugId: drugId,
        }

        try {
            const validateMatch = await axios.post(`${API_URL}/sys/ex-mx/validate-match`, dataMatch);

            if (validateMatch.status === 200 && validateMatch.data.status === true) {
                Swal.fire({
                    title: '¡Duplicado!',
                    text: validateMatch.data.message,
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });

                $('#drugs, #btn-add-drug').val(null);
                return;
            }
        } catch (error) {
            Swal.fire('Error', 'Ocurrió un error al validar la coincidencia. Intente nuevamente.', 'error');
        }

        if ($(`input[value="${drugId}"]`).length > 0) {
            Swal.fire('¡Duplicado!', 'El fármaco ya está en la lista.', 'warning');
            $('#drugs').val(null);
            return;
        }

        const html_data = `
            <tr>
                <td><input type="hidden" name="drug_id[]" value="${drugId}">${drugName}</td>
                <td><input type="text" class="form-control" name="description[]" placeholder="Ingrese descripción"></td>
                <td><input type="text" class="form-control" name="dosis[]" placeholder="Ingrese dosis"></td>
                <td><button type="button" class="btn btn-danger btn-xs btn-remove-drug" value="${drugId}"><i class="bi bi-trash"></i></button></td>
            </tr>
        `;

        if(examId == null){
            $('#tableDrugs').addClass('hide');
            $('#tableDrugs2').removeClass('hide');
        } else {

        }

        $('#tableDrugs tbody').append(html_data);
        $('#drugs').val(null);
        $('#btn-add-drug').val(null);
        alertNotify('success', `<h5>${drug[1]} agregado</h5>`);
    }else{
        Swal.fire('¡Vacío!', 'Escribe algo', 'error');
    }
});

//Función para quitar las filas de la table de recetas
$(document).on('click','.btn-remove-drug', function(){
    $(this).closest('tr').remove();
});

//añadir campo para examen
$('#btn-add-test').on('click', function(){
    html = '<tr><td><input type="text" class="form-control" name="nombre_examen[]" placeholder="Nombre del examen" required></td>';
    html += '<td><input type="file" class="form-control" name="documento[]" required></td>';
    html += '<td><input type="date" class="form-control date" name="fecha_examen[]" required></td>';
    html += '<td><button type="button" class="btn btn-danger remove-row-test"><i class="bi bi-x-lg"></i></button></td></tr>';
    $('#tableTest tbody').append(html);
});
//remover modulos filas de la tabla
$(document).on('click','.remove-row-test', function(){
    $(this).closest('tr').remove();
});

//Función calcular FPP y Edad Gestacional
function getFpp(dateString){
    let fumDate = new Date(dateString);
    // Calcular FPP y formatear como yyyy-mm-dd
    let fppDate = new Date(fumDate);
    fppDate.setDate(fppDate.getDate() + 280);
    let fppFormatted = fppDate.toISOString().split('T')[0];
    // Calcular edad gestacional
    let today = new Date();
    let diffDays = Math.floor((today - fumDate) / (1000 * 60 * 60 * 24));
    let gestationalWeeks = Math.floor(diffDays / 7);
    let gestationalDays = diffDays % 7;
    // Mostrar resultados
    $('#fpp').addClass('is-valid').val(fppFormatted);
    $('#edad_gestacional').addClass('is-valid').val(gestationalWeeks + ' semanas (' + gestationalDays + ' días)');
}

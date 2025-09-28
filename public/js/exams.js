$(document).ready(function(){
    const historyId = $('#historyId').val();
    const examId      = $('#examId').val();
    const anio      = new Date().getFullYear();
    
    const tables = {
        //listado de exámenes por historia clínica
        exams: $('#exam_data').DataTable({ ajax: `${API_URL}/sys/exams/list/${historyId}`, processing: true }),
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
        {
            selector: '.delete-exam',
            endpoint: 'ex',
            table: tables.exams
        },
        {
            selector: '.delete-diagnostic',
            endpoint: 'ex-dx',
            table: tables2.diagnosticId
        },
        {
            selector: '.delete-drug',
            endpoint: 'ex-mx',
            table: tables2.examId
        },
        {
            selector: '.delete-image',
            endpoint: 'ex-img',
            table: tables2.documentId
        }
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
                    return `
                        <button type="button" class="btn btn-info view-row btn-xs" value="${data.record.id}"><i class="bi bi-folder"></i> Ver</button>&nbsp;
                        <button type="button" class="btn btn-success add-new btn-xs" value="${data.record.id}"><i class="bi bi-plus-square-fill"></i> Nuevo</button>
                    `;
                }
            },
        },
        recordsLoaded: (event, data) => {
            //if(permissions.insert == 1){
                $('.add-new').click(function(e){
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = `${API_URL}/sys/exams/new/${id}`;
                });
            //}
            //if(permissions.view == 1){
                $('.view-row').click(function(e) {
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = `${API_URL}/sys/exams/see/${id}`;
                });
            //}
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
    //datepicker
    $('.date').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:' + anio,
        dateFormat: 'yy-mm-dd'
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
            if(response.status == 200 && response.data.status == true){
                $('.text-danger').remove();
                $('.form-group').removeClass('is-invalid is-valid');
                Swal.fire(
                    'Operación exitosa', response.data.message, response.data.type
                ).then((result)=>{
                    if(result.value){
                        window.location.href = response.data.redirect;
                    }
                });
            }else if(response.data.status == false){
                swal.fire({
                    icon: 'error', title: response.data.message, confirmButtonColor: '#3085d6', confirmButtonText: 'Aceptar', cancelButtonText: 'Cancelar'
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
});

//Función calular FPP y Edad Gestacional
function getFpp(dateString){
    let fumDate = new Date(dateString);
    // Calcular la fecha probable de parto (FPP)
    let fppDate = new Date(fumDate);
    fppDate.setDate(fppDate.getDate() + 280); // 280 días = 40 semanas
    // Calcular la edad gestacional actual
    let today = new Date();
    let diffTime = today - fumDate;
    let diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    let gestationalWeeks = Math.floor(diffDays / 7);
    let gestationalDays = diffDays % 7;
    // Mostrar la FPP
    $('#fpp').addClass('is-valid').val(fppDate.toLocaleDateString());
    // Mostrar la edad gestacional
    $('#eg').addClass('is-valid').val(gestationalWeeks + ' semanas (' + gestationalDays + ' días)');
}
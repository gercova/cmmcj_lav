$(document).ready(function(){
    //ID examen
    const examId = document.getElementById('examId').value;
    
    $('.exit-system').on('click', async function(e){
        e.preventDefault();
        try {
            const result = await swal.fire({
                title: '¿Quieres salir del sistema?',
                text: '¿Estás seguro que quieres cerrar la sesión?',
                type: 'warning',   
                showCancelButton: true,   
                confirmButtonColor: "#16a085",   
                confirmButtonText: "Si, salir",
                cancelButtonText: "No, cancelar",
                closeOnConfirm: false,
                animation: "slide-from-top"
            });
            if (result.isConfirmed) {
                const response = await axios.post(`${API_URL}/logout`);
                if(response.status == 200 && response.data.status == true){
                    window.location.href = response.data.redirect;
                }  
            }
        } catch (error) {
            console.error('Error al cerrar la sesión:', error);   
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
            Swal.fire(
                'Error',
                'Ocurrió un error al validar la coincidencia. Intente nuevamente.',
                'error'
            );
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
                Swal.fire(
                    'Error',
                    'Ocurrió un error al validar la coincidencia. Intente nuevamente.',
                    'error'
                );
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
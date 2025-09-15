$(document).ready(function(){

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
                const result = await axios.post(`${API_URL}/diagnostics/search`, {
                    q: request.term // Término de búsqueda
                });
                // Procesar la respuesta y pasar los datos al autocomplete
                response(result.data);
            } catch (error) {
                console.error('Error en la búsqueda:', error);
                response([]); // Enviar un array vacío en caso de error
            }
        },
        minLength: 2,
        select: function(event, ui){
            data = `${ui.item.id}*${ui.item.label}`;
            $('#btn-add-diagnostic').val(data);
        },
    });
	//Funciones para agregar diagnostico
    $('#btn-add-diagnostic').on('click', function(){
        const data = $(this).val();
        if(data != ''){
            const diagnostic        = data.split('*');
            const diagnosticId      = diagnostic[0];
            const diagnosticName    = diagnostic[1];
            if ($(`input[value="${diagnosticId}"]`).length > 0) {
                Swal.fire('¡Duplicado!', 'El diagnóstico ya está en la lista.', 'warning');
                $('#diagnostics').val(null);
                return;
            }
            const html_data = `
                <tr>
                    <td><input type="hidden" name="diagnostic_id[]" value="${diagnosticId}">${diagnosticName}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs btn-remove-diagnosis" value="${diagnosticId}"><i class="bi bi-x-lg"></i></button>
                    </td>
                </tr>
            `;
            $('#tableDiagnostics tbody').append(html_data);
            $('#diagnostics').val(null);
            $('#btn-add-diagnostic').val(null);
            alertNotify('success', `<h5>${diagnosticName} agregado</h5>`);
        }else{
            Swal.fire('¡Vacío!', 'Escribe algo', 'error');
        }
    });
    //Función para quitar las filas de la table de diagnósticos
    $(document).on('click','.btn-remove-diagnosis', function(){
        $(this).closest('tr').remove();
    });
    //Funciones para agregar diagnostico
	$('#drugs').autocomplete({
        source: async function (request, response) {
            try {
                // Realizar la solicitud con Axios
                const result = await axios.post(`${API_URL}/drugs/search`, {
                    q: request.term // Término de búsqueda
                });
                // Procesar la respuesta y pasar los datos al autocomplete
                response(result.data);
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
    $('#btn-add-drug').on('click', function(){
        data = $(this).val();
        if(data){
            const drug = data.split('*');
            const drugId = drug[0];
            const drugName = drug[1];
            if ($(`input[value="${drugId}"]`).length > 0) {
                Swal.fire('¡Duplicado!', 'El fármaco ya está en la lista.', 'warning');
                $('#drugs').val(null);
                return;
            }
            const html_data = `
                <tr>
                    <td><input type="hidden" name="drug_id[]" value="${drugId}">${drugName}</td>
                    <td><input type="text" class="form-control" name="description[]" placeholder="Ingrese descripción"></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs btn-remove-drug" value="${drugId}"><i class="bi bi-x-lg"></i></button>
                    </td>
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
        //title: 'Operación realizada',
        //html: messages,
        title: messages,
    })
}
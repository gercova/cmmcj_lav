$(document).ready(function(){
    //boton modal
	$('#btn-add-diagnostic').click(function(e){
        e.preventDefault();
        $('.form-group').removeClass('is-invalid is-valid');
        $("#codigo").attr('readonly', false);
		$('#diagnosticForm').trigger('reset');
        $('#modalDiagnostic').modal('show');
        $('#id').val(null);
        $('.modal-title').text('Agregar Diagnóstico');
    });
	//formulario diagnostico
	$('#diagnosticForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');

        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
        
        try {
			const response = await axios.post(`${API_URL}/sys/diagnostics/store`, formData);
            if(response.status == 200 && response.data.status == true){
                $('#diagnosticForm').trigger('reset');
                $('#modalDiagnostic').modal('hide');
                LoadRecordsButton.click();
                alertNotify(response.data.type, response.data.message);   
            }else if(response.data.status == false){
                alertNotify(response.data.type, response.data.message);
            }
        } catch(error) {
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
    //tabla index examenes
    $('#diagnostics').jtable({
        title       : "DIAGNÓSTICOS CIE10",
        paging      : true,
        overflow    : scroll,
        sorting     : true,
        actions: {
            listAction: `${API_URL}/sys/diagnostics/list`,
        },
        toolbar: {
            items: [{
                cssClass: 'buscador',
                text: buscador
            }]
        },
        fields: {
            codigo: {
                title: 'CÓDIGO',
                width: '3%',
            },
            descripcion: {
                title: 'DESCRIPCIÓN',
                width: '20%' ,
                sorting: true,
            },
            tipo: {
                title: 'TIPO',
                width: '5%' ,
                sorting: true,
            },
            opciones:{
                title: 'OPCIONES',
                width: '5%',
                sorting: true,
                edit: false,
                create:false,
                display: (data) => {
                    return `
                        <button type="button" class="btn btn-warning update-row btn-xs" value="${data.record.id}"><i class="bi bi-pencil-square"></i> Actualizar</button>&nbsp
                        <button type="button" class="btn btn-danger delete-row btn-xs" value="${data.record.id}"><i class="bi bi-trash"></i> Eliminar</button>
                    `;
                }
            },
        },
        recordsLoaded: (event, data) => {
            //if(permissions.update == 1){
                $('.update-row').click(async function(e){
                    e.preventDefault();
                    const id = $(this).attr('value');
                    const response = await axios.get(`${API_URL}/sys/diagnostics/${id}`)
                    if(response.status == 200) {
                        $('.modal-title').text('Actualizar Diagnóstico');
                        $(".text-danger").remove();
                        $('.form-group').removeClass('is-invalid is-valid');
                        $("#codigo").val(response.data.codigo);
                        $("#descripcion").val(response.data.descripcion);
                        $("#tipo").val(response.data.tipo);
                        $("#id").val(response.data.id);      
                        $('#modalDiagnostic').modal('show');
                    }
                });
            //}
            //if(permissions.delete == 1){
                $('.delete-row').click(async function(e) {
                    e.preventDefault();
                    const id = $(this).attr('value');
                    const result = await Swal.fire({
                        title: '¿Estás seguro de hacerlo?',
                        text: '¡De borrar este diagnóstico!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, borrarlo'
                    });
                    
                    if (result.isConfirmed) {
                        const response = await axios.delete(`${API_URL}/sys/dx/delete/${id}`);
                        if(response.status == 200 && response.data.status == true){
                            console.log(response.data);
                            alertNotify(response.data.type, response.data.message);
                            LoadRecordsButton.click();
                        } else {
                            alertNotify(response.data.type, response.data.message);
                        }
                    }
                });
            //}
        }
    });
    LoadRecordsButton = $('#LoadRecordsButton');
    LoadRecordsButton.click(function (e) {
        e.preventDefault();
        console.log($('#search').val())
        $('#diagnostics').jtable('load', {
            search: $('#search').val()
        });
    });
    LoadRecordsButton.click();
})
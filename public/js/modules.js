$(document).ready(function(){
    const tables = {
        //Listado de diagnosticos por examen
        modules: $('#module_data').DataTable({ ajax: `${API_URL}/sys/modules/list`, searching: true, bLengthChange: true, processing: true, order: [] }),
    };
    //Eliminar un registro
    DeleteHandler.initButtons([
        {
            selector: '.delete-module',
            endpoint: 'mx',
            table: tables.modules
        },
    ]);

	//boton modal farmacos
	$('#btn-add-module').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('is-invalid is-valid');
		$('#moduleForm').trigger('reset');
        $('#moduleForm')[0].reset();
        $('#id').val('');
		$('#modalModule').modal('show');
		$('.modal-title').text('Agregar Módulo');
    });
	//formulario farmacos
	$('#moduleForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');
        
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
			const response = await axios.post(`${API_URL}/sys/modules/store`, formData);
            if(response.status == 200 && response.data.status == true){
				console.log(response);
                $('#moduleForm').trigger('reset');
                $('#modalmodule').modal('hide');
                $('#module_data').DataTable().ajax.reload();
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
	//update item
    $(document).on('click', '.update-row', async function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        const response = await axios.get(`${API_URL}/sys/modules/${id}`);
        if(response.status == 200){
            $('.modal-title').text('Actualizar Fármaco');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('#unidad_medida_id').val(response.data.unidad_medida_id);
            $("#descripcion").val(response.data.descripcion);
            $("#detalle").val(response.data.detalle);
            $("#id").val(response.data.id);      
            $('#modalmodule').modal('show');
        }
    });
});
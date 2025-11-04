$(document).ready(function(){
    const tables = {
        //Listado de diagnosticos por examen
        drugs: $('#drug_data').DataTable({ ajax: `${API_URL}/sys/drugs/list`, searching: true, bLengthChange: true, processing: true, order: [] }),
    };
    //Eliminar un registro
    DeleteHandler.initButtons([
        {
            selector: '.delete-drug',
            endpoint: 'mx',
            table: tables.drugs
        },
    ]);

	//boton modal farmacos
	$('#btn-add-drug').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('is-invalid is-valid');
		$('#drugForm').trigger('reset');
        $('#drugForm')[0].reset();
        $('#id').val('');
		$('#modalDrug').modal('show');
		$('.modal-title').text('Agregar Fármaco');
    });
	//formulario farmacos
	$('#drugForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');
        
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
			const response = await axios.post(`${API_URL}/sys/drugs/store`, formData);
            if(response.status == 200 && response.data.status == true){
				console.log(response);
                $('#drugForm').trigger('reset');
                $('#modalDrug').modal('hide');
                $('#drug_data').DataTable().ajax.reload();
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
        const response = await axios.get(`${API_URL}/sys/drugs/${id}`);
        if(response.status == 200){
            $('.modal-title').text('Actualizar Fármaco');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('#unidad_medida_id').val(response.data.unidad_medida_id);
            $("#descripcion").val(response.data.descripcion);
            $("#detalle").val(response.data.detalle);
            $("#id").val(response.data.id);      
            $('#modalDrug').modal('show');
        }
    });
});
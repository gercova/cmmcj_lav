$(document).ready(function(){
    const tables = {
        //Listado de diagnosticos por examen
        beds: $('#bed_data').DataTable({ ajax: `${API_URL}/sys/beds/list`, searching: true, bLengthChange: true, processing: true, order: []}),
    };
    //Eliminar un registro
    DeleteHandler.initButtons([
        {
            selector: '.delete-bed',
            endpoint: 'bd',
            table: tables.beds
        },
    ]);

	//boton modal farmacos
	$('#btn-add-bed').click(function(e){
        e.preventDefault();
		$('.form-control').removeClass('is-invalid is-valid');
        $('.text-danger').remove();
		$('#bedForm').trigger('reset');
        $('.text-danger').remove();
        $('#id').val(null);
		$('.modal-title').text('Agregar Cama');
        $('#modalBed').modal('show');
    });
	//formulario farmacos
	$('#bedForm').submit(async function(e){
        e.preventDefault();
        $('.form-group').removeClass('is-invalid is-valid');
        $('.text-danger').remove();
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
			const response = await axios.post(`${API_URL}/sys/beds/store`, formData);
            if(response.status == 200 && response.data.status == true){
				console.log(response);
                $('#bedForm').trigger('reset');
                $('#modalBed').modal('hide');
                $('#bed_data').DataTable().ajax.reload();
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
        const response = await axios.get(`${API_URL}/sys/beds/${id}`);
        if(response.status == 200){
            $('.modal-title').text('Actualizar Cama');
		    $('.form-group').removeClass('is-invalid is-valid');
            $(".text-danger").remove();
            $("#description").val(response.data.description);
            $("#floor").val(response.data.floor);
            $("#detail").val(response.data.detail);
            $("#id").val(response.data.id);      
            $('#modalBed').modal('show');
        }
    });
});
$(document).ready(function(){
    let slimSelect;
    const tables = {
        //Listado de diagnosticos por examen
        specialties: $('#specialty_data').DataTable({ ajax: `${API_URL}/sys/specialties/list`, searching:true, bLengthChange:true, processing:true, order:[]}),
    };
    //Eliminar un registro
    DeleteHandler.initButtons([
        {
            selector: '.delete-specialty',
            endpoint: 'specialty',
                table: tables.specialties
        },
    ]);

	//boton modal farmacos
	$('#btn-add-specialty').click(function(e){
        e.preventDefault();
        if (slimSelect) slimSelect.destroy();
        slimSelect = new SlimSelect({
            select: '#ocupacion_id',
            placeholder: 'Seleccione un cargo',
            allowDeselect: true
        });
		$('.form-group').removeClass('is-invalid is-valid');
		$('#specialtyForm').trigger('reset');
        $('#specialtyForm')[0].reset();
        $('#ocupacion_id').val('');
        $('#id').val('');
		$('#modalSpecialty').modal('show');
		$('.modal-title').text('Agregar Especialidad');
    });
	//formulario farmacos
	$('#specialtyForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');
        
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
			const response = await axios.post(`${API_URL}/sys/specialties/store`, formData);
            if(response.status == 200 && response.data.status == true){
				console.log(response);
                $('#specialtyForm').trigger('reset');
                $('#modalSpecialty').modal('hide');
                $('#specialty_data').DataTable().ajax.reload();
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
        const id = $(this).attr('value');
        if (slimSelect) slimSelect.destroy();
        slimSelect = new SlimSelect({
            select: '#ocupacion_id',
            placeholder: 'Seleccione un cargo',
            allowDeselect: true
        });
        const response = await axios.get(`${API_URL}/sys/specialties/${id}`);
        if(response.status == 200){
            $('.modal-title').text('Actualizar Especialidad');
            $(".text-danger").remove();
		    $('.form-group').removeClass('is-invalid is-valid');
            slimSelect.set(response.data.ocupacion_id);
            //$('#ocupacion_id').val(response.data.ocupacion_id);
            $("#descripcion").val(response.data.descripcion);
            $("#id").val(response.data.id);      
            $('#modalSpecialty').modal('show');
        }
    });
});
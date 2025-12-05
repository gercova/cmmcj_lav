$(document).ready(function(){
    const tables = {
        //Listado de ocupaciones
        occupation: $('#occupation_data').DataTable({ ajax:`${API_URL}/sys/occupations/list`, searching:true, bLengthChange:true, processing:true, order:[]}),
    };
    //Eliminar un registro
    DeleteHandler.initButtons([
        {
            selector: '.delete-occupation',
            endpoint: 'oc',
            table: tables.occupation
        },
    ]);
	//boton modal
	$('#btn-add-occupation').click(function(e){
        e.preventDefault();
        $('#occupationForm').trigger('reset');
		$('.form-control').removeClass('is-invalid is-valid');
		$('.text-danger').remove();
        $('#id').val(null);
        $('.modal-title').text('Agregar Ocupación');
        $('#modalOccupation').modal('show');
    });
	//formulario categoria
	$('#occupationForm').off('submit').on('submit', async function(e){
        e.preventDefault();
        $('.form-control').removeClass('is-invalid is-valid');
        $('.text-danger').remove();

        const formData = $(this).serialize();
        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
       
		try {
			const response = await axios.post(`${API_URL}/sys/occupations/store`, formData);
            if(response.status == 200 && response.data.status == true){
                $('#occupationForm').trigger('reset');
                $('#modalOccupation').modal('hide');
                $('#occupation_data').DataTable().ajax.reload();
                alertNotify(response.data.type, response.data.message);
            }else if(response.data.status == false){
                alertNotify(response.data.type, response.data.message);
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
	//update item
    $(document).on('click', '.update-row', async function(e) {
        e.preventDefault();
        const id = $(this).attr('value');
        $('#occupationForm').trigger('reset');
        $('.form-control').removeClass('is-invalid is-valid');
        const response = await axios.get(`${API_URL}/sys/occupations/${id}`);
        try {
            if(response.status == 200){
                $('.modal-title').text('Actualizar Ocupación');
                $(".text-danger").remove();
                $("#descripcion").val(response.data.descripcion);
                $("#id").val(response.data.id);      
                $('#modalOccupation').modal('show');
            }
        } catch (err) {
            console.log(err);
        }
    });
})
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
    //boton modal Submodule
	$('#btn-add-submodule').click(function(e){
        e.preventDefault();
		$('.form-control').removeClass('is-valid is-invalid');
		$('#submoduleForm').trigger('reset');
        $('#submoduleForm').find('.text-danger').remove();
        $('#submoduleId').val('');
        $('#modalSubmodule').modal('show');
        $('.modal-title').text('Agregar Submódulo');
    });
	//formulario module
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
                $('#modalModule').modal('hide');
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

    //formulario submodule
	$('#submoduleForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');
        
        const formData = $(this).serialize();

        const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

        try {
			const response = await axios.post(`${API_URL}/sys/modules/sub/store`, formData);
            if(response.status == 200 && response.data.status == true){
				console.log(response);
                $('#submoduleForm').trigger('reset');
                $('#modalSubmodule').modal('hide');
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
    $(document).on('click', '.update-row-module', async function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        const response = await axios.get(`${API_URL}/sys/modules/${id}`);
        if(response.status == 200){
            $('.modal-title').text('Actualizar Módulo');
            $(".text-danger").remove();
		    $('.form-group').removeClass('is-invalid is-valid');
            $("#descripcion").val(response.data.descripcion);
            $("#id").val(response.data.id);      
            $('#modalSubmodule').modal('show');
        }
    });
    //function get submodule by id
    $(document).on('click', '.update-row-submodule', async function(e) {
        e.preventDefault();
        const id = $(this).attr('value');
        try {
            const response = await axios.get(`${API_URL}/sys/modules/sub/${id}`);
            if (response.status == 200) {
                console.log(response.data);
                $('.modal-title').text('Actualizar Submódulo');
                $(".text-danger").remove();
                $('.form-control').removeClass('is-invalid is-valid');
                $("#module_id").val(response.data.module_id);
                $("#sm_nombre").val(response.data.nombre);
                $("#sm_descripcion").val(response.data.descripcion);
                $("#sm_icono").val(response.data.icono);
                $('#submoduleId').val(response.data.id);      
                $('#modalSubmodule').modal('show');
            }   
        } catch (error) {
            console.log(error);
        }
    });
});
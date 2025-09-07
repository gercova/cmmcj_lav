let manageSpecialtyTable;

$(document).ready(function(){
    manageSpecialtyTable = $("#specialty_data").DataTable({
		'ajax': _baseurl_ + _routecontroller_ +'/list',
		'order': []			
	});
	//boton modal
	$('#btn-add-specialty').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('is-valid').removeClass('is-invalid');
        $('#formSpecialty').trigger('reset');
        $('#formSpecialty')[0].reset();
        $('#specialtyId').val('');
        $('#modalSpecialty').modal('show');
        $('.modal-title').text('Agregar Especialidad');
    });
	//formulario categoria
	$('#formSpecialty').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid').removeClass('has-success');	
        let descripcion = $('#descripcion').val();
        if(descripcion == ''){
            $('#descripcion').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#descripcion').closest('.form-group').addClass('is-invalid');
        }else{
            $('#descripcion').find('.text-danger').remove();
            $('#descripcion').closest('.form-group').addClass('is-valid');	  	
        }
        
        if(descripcion){
			const formData = new FormData(this);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.success == true){
					$('#formSpecialty').trigger('reset');
                    $('#formSpecialty')[0].reset();
					$('#formSpecialty').modal('hide');
					$('#specialty_data').DataTable().ajax.reload();
					Swal.fire(
						'Hecho',
						response.messages,
						'success'
					);
					$('input[name="csrf_token"]').val(response.token);
				}else if(response.success == false){
					swal.fire({
						icon: 'error',
						title: response.messages,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Aceptar',
					});
					$('#descripcion').closest('.form-group').addClass('is-invalid');
					$('input[name="csrf_token"]').val(response.token);
				}
			}).catch(error => console.error(error));
            return false; 
        }
    });
	//update item
    $(document).on('click', '.update-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        fetch(`${_baseurl_ + _routecontroller_}/view/${id}`)
        .then(res => res.json())
        .then(res => {
            $('.modal-title').text('Actualizar Especialidad');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
            $("#descripcion").val(res.data.descripcion);
            $("#specialtyId").val(res.data.id);      
            $('#modalSpecialty').modal('show');
        }).catch(function(err) {
            console.log(err);
        });
        //return true;
    });
    //delete item
    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            title: '¿Estás seguro de hacerlo?',
            text: '¡De borrar la categoria!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                fetch(`${_baseurl_ + _routecontroller_}/delete/${id}`)
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    Swal.fire(
                        '¡Borrado!',
                        res.messages,
                        'success'
                    );
                    $('#specialty_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
})
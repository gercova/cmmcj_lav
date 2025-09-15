let manageCategoriesTable;

$(document).ready(function(){
    manageCategoriesTable = $("#ocupation_data").DataTable({
		'ajax': _baseurl_ + _routecontroller_ +'/list',
		'order': []			
	});
	//boton modal
	$('#btn-ocupation').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('has-error').removeClass('has-success');
		$('#formOcupation').trigger('reset');
        $('#formOcupation')[0].reset();
        $('#ocupationId').val('');
        $('#modalOcupation').modal('show');
        $('.modal-title').text('Agregar Ocupación');
    });
	//formulario categoria
	$('#formOcupation').off('submit').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');	
        let descripcion = $('#descripcion').val();
        if(descripcion == ''){
            $('#descripcion').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#descripcion').closest('.form-group').addClass('has-error');
        }else{
            $('#descripcion').find('.text-danger').remove();
            $('#descripcion').closest('.form-group').addClass('has-success');	  	
        }
        
        if(descripcion){
			let data = {
				'descripcion': descripcion,
				'token': _token_
			};
			const formData = new FormData(this);
			formData.append('descripcion', data.descripcion);
			formData.append('token', data.token);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.success == true){
					$('#formOcupation').trigger('reset');
					$('#modalOcupation').modal('hide');
					$('#ocupation_data').DataTable().ajax.reload();
					swal.fire(
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
					$('#descripcion').closest('.form-group').addClass('has-error');
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
            $('.modal-title').text('Actualizar Ocupación');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
            $("#descripcion").val(res.data.descripcion);
            $("#ocupationId").val(res.data.id);      
            $('#modalOcupation').modal('show');
        }).catch(function(err) {
            console.log(err);
        });
    });
    //delete item
    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            title: '¿Estás seguro de hacerlo?',
            text: '¡De borrar este registro!',
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
                    $('#ocupation_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
})
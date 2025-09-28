$(document).ready(function(){
    manageCategoriesTable = $("#category_data").DataTable({
		'ajax': _baseurl_ + _routecontroller_ +'/list',
		'order': []			
	});
	//boton modal
	$('#btn-add-category').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('is-valid').removeClass('is-invalid');
		$('#formCategory').trigger('reset');
        $('#modalCategory').modal('show');
        $('.modal-title').text('Agregar Categoría');
    });
	//formulario categoria
	$('#formCategory').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');	
        
			
			const formData = new FormData(this);
			formData.append('descripcion', data.descripcion);
			formData.append('detalle', data.detalle);
			formData.append('token', data.token);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.success == true){
					$('#formCategory').trigger('reset');
					$('#modalCategory').modal('hide');
					$('#category_data').DataTable().ajax.reload();
					Swal.fire(
						'Hecho',
						response.messages,
						'success'
					);
				}else if(response.success == false){
					swal.fire({
						icon: 'error',
						title: response.messages,
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Aceptar',
					});
				}
			}).catch(error => console.error(error));
            
        
    });
	//update item
    $(document).on('click', '.update-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        fetch(`${_baseurl_ + _routecontroller_}/view/${id}`)
        .then(res => res.json())
        .then(res => {
            $('.modal-title').text('Actualizar Categoría');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
            $("#descripcion").val(res.data.descripcion);
			$("#detalle").val(res.data.detalle);
            $("#categoryId").val(res.data.id);      
            $('#modalCategory').modal('show');
        }).catch(function(err) {
            console.log(err);
        });
        //return true;
    });
})
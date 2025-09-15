let managePermissionsTable;

$(document).ready(function(){
    managePermissionsTable = $("#permissions_data").DataTable({
		'ajax': `${_baseurl_ + _routecontroller_}/list`,
		'order': []			
	});
	//boton modal permisos
	$('#btn-add-permission').click(function(e){
        e.preventDefault();
        $('#formPermission').trigger('reset');
        $('#formPermission')[0].reset();
        $('#permissionId').val('');
		$('.form-group').removeClass('has-error').removeClass('has-success');
        $('#modalPermission').modal('show');
        $('.modal-title').text('Agregar Permiso');
    });
	//formulario permisos
	$('#formPermission').off('submit').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');	
        var menu    = $('#menu').val();
        var perfil  = $('#perfil').val();
        var read    = $('#read').val();
        var view    = $('#view').val();
        var insert  = $('#insert').val();
        var update  = $('#update').val();
        var drop    = $('#drop').val();
        if(menu == ''){
            $('#menu').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#menu').closest('.form-group').addClass('has-error');
        }else{
            $('#menu').find('.text-danger').remove();
            $('#menu').closest('.form-group').addClass('has-success');	  	
        }
        if(perfil == ''){
            $('#perfil').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#perfil').closest('.form-group').addClass('has-error');
        }else{
            $('#perfil').find('.text-danger').remove();
            $('#perfil').closest('.form-group').addClass('has-success');	  	
        }
        if(read == ''){
            $('#read').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#read').closest('.form-group').addClass('has-error');
        }else{
            $('#read').find('.text-danger').remove();
            $('#read').closest('.form-group').addClass('has-success');	  	
        }
        if(view == ''){
            $('#view').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#view').closest('.form-group').addClass('has-error');
        }else{
            $('#view').find('.text-danger').remove();
            $('#view').closest('.form-group').addClass('has-success');	  	
        }
        if(insert == ''){
            $('#insert').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#insert').closest('.form-group').addClass('has-error');
        }else{
            $('#insert').find('.text-danger').remove();
            $('#insert').closest('.form-group').addClass('has-success');	  	
        }
        if(update == ''){
            $('#update').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#update').closest('.form-group').addClass('has-error');
        }else{
            $('#update').find('.text-danger').remove();
            $('#update').closest('.form-group').addClass('has-success');	  	
        }
        if(drop == ''){
            $('#drop').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#drop').closest('.form-group').addClass('has-error');
        }else{
            $('#drop').find('.text-danger').remove();
            $('#drop').closest('.form-group').addClass('has-success');	  	
        }
        if(menu && perfil && read && view && insert && update && drop){
			let data = {
				'menu'      : menu,
				'perfil'    : perfil,
                'read'      : read,
                'view'      : view,
                'insert'    : insert,
                'update'    : update,
                'drop'      : drop,
				'token'     : _token_
			};
			const formData = new FormData(this);
			formData.append('menu', data.menu);
			formData.append('perfil', data.perfil);
            formData.append('read', data.read);
            formData.append('view', data.view);
            formData.append('insert', data.insert);
            formData.append('update', data.update);
            formData.append('drop', data.drop);
			formData.append('token', data.token);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.success == true){
					$('#formPermission').trigger('reset');
					$('#modalPermission').modal('hide');
					$('#permissions_data').DataTable().ajax.reload();
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
                    $('input[name="csrf_token"]').val(response.token);
                    $('#menu').closest('.form-group').addClass('has-error');
                    $('#perfil').closest('.form-group').addClass('has-error');
				}
			}).catch(error => console.error(error));
            return false; 
        }
    });
	//update item permiso
    $(document).on('click', '.update-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        fetch(`${_baseurl_ + _routecontroller_}/view/${id}`, {
            method: 'GET',
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);
            $('.modal-title').text('Actualizar Permisos');
            $(".text-danger").remove();
		    // remove the form error
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
            $("#menu").val(res.data.menu_id);
            $("#perfil").val(res.data.perfil_id);
            $("#read").val(res.data.read);
            $("#view").val(res.data.view);
            $("#insert").val(res.data.insert);
            $("#update").val(res.data.update);
            $("#drop").val(res.data.delete);
            $('#permissionId').val(id);
            $('#modalPermission').modal('show');
        }).catch(function(err) {
            console.log(err);
        });
    });
    //delete item permiso
    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            title: '¿Estás seguro de hacerlo?',
            text: '¡De borrar este permiso!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                const url = _baseurl_ + 'sgt/' + _routecontroller_ + `/delete/${id}/${_table_}`;
                fetch(url, {
                    method: 'DELETE',
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    Swal.fire(
                        '¡Borrado!',
                        res.messages,
                        'success'
                    );
                    $('#permissions_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
});
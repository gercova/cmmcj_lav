let manageUsersTable;
$(document).ready(function(){
    manageUsersTable = $("#user_data").DataTable({
		'ajax': _baseurl_ + _routecontroller_ +'/list',
		'order': []			
	});
    //validar dni
    $('#dni').off().on('change', function() {
        let dni = $(this).val();
        let regex = /^\d{8}$/;
        if (!regex.test(dni)) {
            swal.fire({
                icon: 'error',
                title: 'Ingrese un número de 8 dígitos',
            });
        }
    });
    //tipear número DNI
    $('#dni').keyup(function(){
        let dni = $(this).val();
        if(dni.length == 8){
            consultaDatosSUNAT(dni);
        }
    });
	//boton modal
	$('#btn-add-user').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('has-error').removeClass('has-success');
		$('#formUser').trigger('reset');
        $('#formUser')[0].reset();
        $('#userId').val('');
        $('#modalUser').modal('show');
        $('.modal-title').text('Agregar Usuario');
    });
	//formulario categoria
	$('#formUser').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');	
        let dni             = $('#dni').val();
        let nombres         = $('#nombres').val();
        let username        = $('#username').val();
        let clave           = $('#clave').val();
        let celular         = $('#celular').val();
        let perfil          = $('#perfil').val();
        let cmp             = $('#cmp').val();
        let rne             = $('#rne').val();
        let especialidad    = $('#especialidad').val();
        if(dni == ''){
            $('#dni').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#dni').closest('.form-group').addClass('has-error');
        }else{
            $('#dni').find('.text-danger').remove();
            $('#dni').closest('.form-group').addClass('has-success');	  	
        }
        if(nombres == ''){
            $('#nombres').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#nombres').closest('.form-group').addClass('has-error');
        }else{
            $('#nombres').find('.text-danger').remove();
            $('#nombres').closest('.form-group').addClass('has-success');	  	
        }
        if(username == ''){
            $('#username').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#username').closest('.form-group').addClass('has-error');
        }else{
            $('#username').find('.text-danger').remove();
            $('#username').closest('.form-group').addClass('has-success');	  	
        }
        if(clave == ''){
            $('#clave').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#clave').closest('.form-group').addClass('has-error');
        }else{
            $('#clave').find('.text-danger').remove();
            $('#clave').closest('.form-group').addClass('has-success');	  	
        }
        if(celular == ''){
            $('#celular').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#celular').closest('.form-group').addClass('has-error');
        }else{
            $('#celular').find('.text-danger').remove();
            $('#celular').closest('.form-group').addClass('has-success');	  	
        }
        if(cmp == ''){
            $('#cmp').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#cmp').closest('.form-group').addClass('has-error');
        }else{
            $('#cmp').find('.text-danger').remove();
            $('#cmp').closest('.form-group').addClass('has-success');	  	
        }
        if(rne == ''){
            $('#rne').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#rne').closest('.form-group').addClass('has-error');
        }else{
            $('#rne').find('.text-danger').remove();
            $('#rne').closest('.form-group').addClass('has-success');	  	
        }
        if(perfil == ''){
            $('#perfil').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#perfil').closest('.form-group').addClass('has-error');
        }else{
            $('#perfil').find('.text-danger').remove();
            $('#perfil').closest('.form-group').addClass('has-success');	  	
        }
        if(especialidad == ''){
            $('#especialidad').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#especialidad').closest('.form-group').addClass('has-error');
        }else{
            $('#especialidad').find('.text-danger').remove();
            $('#especialidad').closest('.form-group').addClass('has-success');	  	
        }
        
        if(dni && nombres && username && clave && celular && cmp && rne && perfil && especialidad){
            const formData = new FormData(this);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.success == true){
					$('#formUser').trigger('reset');
					$('#modalUser').modal('hide');
					$('#user_data').DataTable().ajax.reload();
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
					$('#dni').closest('.form-group').addClass('has-error');
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
        fetch(`${_baseurl_ + _routecontroller_}/view/${id}`, {
            method: 'GET',
        })
        .then(res => res.json())
        .then(res => {
            $('.modal-title').text('Actualizar Usuario');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
            $("#dni").val(res.data.dni);
            $("#nombres").val(res.data.nombres);
            $("#username").val(res.data.usuario);
            $("#clave").val(res.data.oldpass);
            $("#celular").val(res.data.celular);
            $("#cmp").val(res.data.cmp);
            $("#rne").val(res.data.rne);
            res.data.view_cmp_rne !== null ? $("#vcr").prop('checked', true) : $("#vcr").prop('checked', false);
            $("#perfil").val(res.data.perfil_id);
            $("#especialidad").val(res.data.especialidad_id);
            $("#userId").val(res.data.id);      
            $('#modalUser').modal('show');
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
            text: '¡De borrar este usuario!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                fetch(`${_baseurl_ + _routecontroller_}/delete/${id}`, {
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
                    $('#user_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
});
//función encontrar nombres por DNI
function consultaDatosSUNAT(dni){
    const url = `${_baseurl_}hcl/historias/consultar_dni`;
    let data = {
        'dni': dni,
        'token': _token_
    };
    const formData = new FormData();
    formData.append('dni', data.dni);
    formData.append('token', data.token);
    fetch(url, {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);
        if(data){
            $('#dni').closest('.col-lg-2').removeClass('has-error');
            $('#dni').closest('.col-lg-2').addClass('has-success');
            $('#nombres').closest('.col-lg-6').addClass('has-success');
            $('#dni').val(data.numeroDocumento);
            $('#nombres').val(data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno);

            const fullnames = `${data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno}`
            let data2 = {
                'fullnames': fullnames,
                'token': _token_
            };
            const formData = new FormData();
            formData.append('fullnames', data2.fullnames);
            formData.append('token', data2.token);
            fetch(`${_baseurl_ + _routecontroller_}/createNickname`, {
                method: 'POST',
                body: formData,
            })
            .then(res => res.json())
            .then(res => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                }); 
                Toast.fire({
                    icon: 'success',
                    title: res.messages
                });
                $('#username').val(res.nickname).focus();
            }).catch(error => console.error(error));
        }else{
            $("#dni").val('');
            $("#nombres").val('');
            $('#username').val('');
        }
    }).catch(error => console.error(error));
}
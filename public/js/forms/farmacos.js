let manageDrugsTable;

$(document).ready(function(){
    manageDrugsTable = $("#drug_data").DataTable({
		'ajax': `${_baseurl_ + _routecontroller_}/list`,
		'order': []			
	});
	//boton modal farmacos
	$('#btn-drug').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('has-error').removeClass('has-success');
		$('#formDrug').trigger('reset');
        $('#formDrug')[0].reset();
        $('#drugId').val('');
		$('#modalDrug').modal('show');
		$('.modal-title').text('Agregar Fármaco');
    });
	//formulario farmacos
	$('#formDrug').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');	
        let categoria   = $('#categoria').val();
        let descripcion = $('#descripcion').val();
        let presentacion = $('#presentacion').val();
        if(categoria    == ''){
            $('#categoria').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#categoria').closest('.form-group').addClass('has-error');
        }else{
            $('#categoria').find('.text-danger').remove();
            $('#categoria').closest('.form-group').addClass('has-success');
        }
        if(descripcion == '') {
            $('#descripcion').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#descripcion').closest('.form-group').addClass('has-error');
        }else{
            $('#descripcion').find('.text-danger').remove();
            $('#descripcion').closest('.form-group').addClass('has-success');	  	
        }
        if(presentacion == '') {
            $('#presentacion').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#presentacion').closest('.form-group').addClass('has-error');
        }else{
            $('#presentacion').find('.text-danger').remove();
            $('#presentacion').closest('.form-group').addClass('has-success');	  	
        }
        if(categoria && descripcion && presentacion){
            const formData = new FormData(this);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(res => {
				console.log(res);
                if(res.success == true){
                    $('#formDrug').trigger('reset');
                    $('#modalDrug').modal('hide');
                    $('#drug_data').DataTable().ajax.reload();
                    Swal.fire(
                        'Hecho',
                        res.messages,
                        'success'
                    );
                    $('input[name="csrf_token"]').val(res.token);
                }else if(res.success == false){
                    swal.fire({
                        icon: 'error',
                        title: res.messages,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar',
                    });
                    $('#descripcion').closest('.form-group').addClass('has-error');
                    $('input[name="csrf_token"]').val(res.token);
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
            $('.modal-title').text('Actualizar Fármaco');
            $(".text-danger").remove();
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
			$("#categoria").val(res.data.categoria_id);
            $("#descripcion").val(res.data.descripcion);
            $('#presentacion').val(res.data.tpd_id);
            $("#drugId").val(res.data.id);      
            $('#modalDrug').modal('show');
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
            text: '¡De borrar el fármaco!',
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
                    $('#drug_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
});
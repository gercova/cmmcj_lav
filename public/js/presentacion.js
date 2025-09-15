let manageDrugsTable;
let manageTDPTable;

$(document).ready(function(){
    manageTDPTable = $("#tdp_data").DataTable({
		'ajax': _baseurl_ + _routecontroller_ +'/list',
		'order': []			
	});

    //boton modal presentacion farmacos
	$('#btn-tpd').click(function(e){
        e.preventDefault();
		$('.form-group').removeClass('has-error').removeClass('has-success');
        $('#formTDP').trigger('reset');
        $('#formTDP')[0].reset();
        $('#tdpId').val('');
		$('#modalTDP').modal('show');
		$('.modal-title').text('Agregar Tipo Presentación de Fármaco');
    });
    //formulario tipo presentación de fármaco
	$('#formTDP').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');	
        let descripcion = $('#descripcion').val();
        let abreviatura = $('#abreviatura').val();
        if(descripcion == '') {
            $('#descripcion').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#descripcion').closest('.form-group').addClass('has-error');
        }else{
            $('#descripcion').find('.text-danger').remove();
            $('#descripcion').closest('.form-group').addClass('has-success');
        }
        if(abreviatura == ''){
            $('#abreviatura').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#abreviatura').closest('.form-group').addClass('has-error');
        }else{
            $('#abreviatura').find('.text-danger').remove();
            $('#abreviatura').closest('.form-group').addClass('has-success');
        }
        if(descripcion && abreviatura){
            fetch(`${_baseurl_ + _routecontroller_}/store`, {
                method: 'POST',
                body: new FormData(this),
            })
            .then(res => res.json())
            .then(res => {
                if(res.success == true){
                    $('#formTDP').trigger('reset');
                    $('#modalTDP').modal('hide');
                    $('#tdp_data').DataTable().ajax.reload();
                    Swal.fire(
                        'Hecho',
                        res.messages,
                        'success'
                    );
                    $('input[name="csrf_token"]').val(res.token);
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
            });
            return false;
        }
    });
	//update item
    $(document).on('click', '.update-row', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        const url = _baseurl_ + _routecontroller_ +  `/view/${id}`;
        fetch(url)
        .then(res => res.json())
        .then(res => {
            $('.modal-title').text('Actualizar Fármaco');
            $(".text-danger").remove();
		    // remove the form error
		    $('.form-group').removeClass('has-error').removeClass('has-success');
            $('input[name="csrf_name"]').val(res.token);
            $("#descripcion").val(res.data.descripcion);
            $("#abreviatura").val(res.data.alias);
            $("#tdpId").val(res.data.id);      
            $('#modalTDP').modal('show');

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
                const url = _baseurl_ + _routecontroller_ + `/delete/${id}`;
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    Swal.fire(
                        '¡Borrado!',
                        res.messages,
                        'success'
                    );
                    $('#tdp_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
});
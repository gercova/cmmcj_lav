let manageDiagnosisTable;

$(document).ready(function(){
    manageDiagnosisTable = $("#diagnosis_data").DataTable({
		'ajax': _baseurl_ + _routecontroller_ +'/list',
		'order': []			
	});
    //tabla index examenes
    $('#diagnosticos').jtable({
        title       : "DIAGNÓSTICOS CIE10",
        paging      : true,
        overflow    : scroll,
        sorting     : true,
        actions: {
            listAction: _baseurl_ + 'mtmt/diagnosticos/list',
        },
        toolbar: {
            items: [{
                cssClass: 'buscador',
                text: buscador
            }]
        },
        fields: {
            cod: {
                title: 'CÓDIGO',
                width: '3%',
            },
            descripcion: {
                title: 'DESCRIPCIÓN',
                width: '20%' ,
                sorting: true,
            },
            opciones:{
                title: 'OPCIONES',
                width: '5%',
                sorting: true,
                edit: false,
                create:false,
                display: (data) => {
                    return (permissions.update == 1 ? `<button type="button" class="btn btn-warning update-row btn-xs" value="${data.record.id}"><i class="bi bi-pencil-square"></i> Actualizar</button>`:``)+'&nbsp;'+(permissions.delete == 1 ?`<button type="button" class="btn btn-danger delete-row btn-xs" value="${data.record.id}"><i class="bi bi-trash"></i> Eliminar</button>`:``);
                }
            },
        },
        recordsLoaded: (event, data) => {
            if(permissions.update == 1){
                $('.update-row').click(function(e){
                    e.preventDefault();
                    let id = $(this).attr('value');
                    fetch(`${_baseurl_ + _routecontroller_}/view/${id}`)
                    .then(res => res.json())
                    .then(res => {
                        $('.modal-title').text('Actualizar Diagnóstico');
                        $(".text-danger").remove();
                        $('.form-group').removeClass('has-error').removeClass('has-success');
                        $('input[name="csrf_name"]').val(res.token);
                        $("#codigo").val(res.data.cod).attr('readonly', true);
                        $("#descripcion").val(res.data.descripcion);
                        $("#diagnosisId").val(res.data.id);      
                        $('#modalDiagnosis').modal('show');
                    }).catch(function(err) {
                        console.log(err);
                    });
                });
            }
            if(permissions.delete == 1){
                $('.delete-row').click(function(e) {
                    e.preventDefault();
                    let id = $(this).attr('value');
                    Swal.fire({
                        title: '¿Estás seguro de hacerlo?',
                        text: '¡De borrar este diagnóstico!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, borrarlo'
                    }).then((result) => {
                        if (result.value) {
                            fetch(`${_baseurl_ + +_routecontroller_}/delete/${id}`)
                            .then(res => res.json())
                            .then(res => {
                                console.log(res);
                                Swal.fire(
                                    '¡Borrado!',
                                    res.messages,
                                    'success'
                                );
                                LoadRecordsButton.click();
                            }).catch(function(err) {
                                console.log(err);
                            });
                        }
                    })
                });
            }
        }
    });
    LoadRecordsButton = $('#LoadRecordsButton');
    LoadRecordsButton.click(function (e) {
        e.preventDefault();
        console.log($('#search').val())
        $('#diagnosticos').jtable('load', {
            search: $('#search').val()
        });
    });
    LoadRecordsButton.click();
	//boton modal
	$('#btn-diagnosis').click(function(e){
        e.preventDefault();
        $('.form-group').removeClass('has-error').removeClass('has-success');
        $("#codigo").attr('readonly', false);
		$('#formDiagnosis').trigger('reset');
        $('#modalDiagnosis').modal('show');
        $('.modal-title').text('Agregar Diagnóstico');
    });
	//formulario diagnostico
	$('#formDiagnosis').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');	
        let diagnosis   =  $('#descripcion').val();
        if(diagnosis == ''){
            $('#descripcion').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#descripcion').closest('.form-group').addClass('has-error');
        }else{
            $('#descripcion').find('.text-danger').remove();
            $('#descripcion').closest('.form-group').addClass('has-success');	  	
        }
        
        if(diagnosis){
			let data = {
				'descripcion': diagnosis,
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
					$('#formDiagnosis').trigger('reset');
					$('#modalDiagnosis').modal('hide');
					$('#diagnosis_data').DataTable().ajax.reload();
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
					$('#descripcion').closest('.form-group').addClass('has-error');
					$('input[name="csrf_token"]').val(response.token);
				}
			}).catch(error => console.error(error));
            return false; 
        }
    });
})
let manageTestTable, manageImagesTable, manageDiagnosisTable, manageMedicationTable, manageDocumentTable;

$(document).ready(function(){
    const historyId = $('#historyId').val();
    const testId    = $('#testId').val();
    manageTestTable = $("#test_data").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/list/${historyId}`,
        'order': [0, 'asc']
    });
    //Listado de examenes radiologicos por examen
    manageImagesTable = $("#images_data").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/listImages/${testId}`,
        'order': [[1, 'asc']],
        'searching': false,
        'bLengthChange': false,
    });
    //Listado de diagnosticos por examen
    manageDiagnosisTable = $("#diagnosis_data").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/listDiagnosis/${testId}`,
        'order': [[0, 'asc']],
        'searching': false,
        'bLengthChange': false,
    });
    //Receta por examen
    manageMedicationTable = $("#recipes_data").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/listMedication/${testId}`,
        'order': [[0, 'asc']],
        'searching': false,
        'bLengthChange': false,
    });
    //Tabla de pdf 
    manageDocumentTable = $("#document_data").DataTable({
        'ajax': `${_baseurl_ + _routecontroller_}/listDocument/${testId}`,
        'order': [[0, 'asc']],
        'searching': false,
        'bLengthChange': false,
    })
    //remover modulos filas de la tabla
    $(document).on('click','.btn-remove-img', function(){
        $(this).closest('tr').remove();
    });
    //tabla index examenes
    $('#examenes').jtable({
        title       : "EXÁMENES CLÍNICOS",
        paging      : true,
        overflow    : scroll,
        sorting     : true,
        actions: {
            listAction: `${_baseurl_}hcl/historias/list`,
        },
        toolbar: {
            items: [{
                cssClass: 'buscador',
                text: buscador
            }]
        },
        fields: {
            fecha: {
                key: false,
                title: 'FECHA',
                width: '6%' ,
            },
            nro_historia: {
                title: 'HISTORIA',
                width: '8%',
            },
            dni: {
                key: true,
                title: 'DNI',
                width: '6%' ,

            },
            nombres: {
                title: 'NOMBRES',
                width: '20%',

            },
            fecha_nacimiento: {
                title: 'F.N',
                width: '6%',

            },
            edad: {
                title: 'EDAD',
                width: '4%',
            },
            sexo: {
                title: 'SEXO',
                width: '4%' ,
            },
            ver:{
                title: 'OPCIONES',
                width: '10%',
                sorting:false,
                edit:false,
                create:false,
                display: (data) => {
                    return (permissions.view == 1 ? `<button type="button" class="btn btn-info view-row btn-xs" value="${data.record.id}"><i class="bi bi-folder"></i> Ver</button>`:``)+'&nbsp;'+(permissions.insert == 1 ?`<button type="button" class="btn btn-success add-new btn-xs" value="${data.record.id}"><i class="bi bi-plus-square-fill"></i> Nuevo</button>`:``);
                }
            },
        },
        recordsLoaded: (event, data) => {
            if(permissions.insert == 1){
                $('.add-new').click(function(e){
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = `${_baseurl_ + _routecontroller_}/add/${id}`;
                });
            }
            if(permissions.view == 1){
                $('.view-row').click(function(e) {
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = `${_baseurl_ + _routecontroller_}/view/${id}`;
                });
            }
        }
    });
    LoadRecordsButton = $('#LoadRecordsButton');
    LoadRecordsButton.click(function (e) {
        e.preventDefault();
        console.log($('#search').val())
        $('#examenes').jtable('load', {
            search: $('#search').val()
        });
    });
    LoadRecordsButton.click();
    //calcular imc
    $('#peso, #talla').on('input', function() {
        let peso = parseFloat($('#peso').val());
        let altura = parseFloat($('#talla').val());
        if (!isNaN(peso) && !isNaN(altura) && altura > 0) {
            let imc = peso / (altura * altura);
            $('#imc').val(imc.toFixed(2));
        }
    });
    //datepicker
    $('.date').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: '1920:' + anio,
        dateFormat: 'yy-mm-dd'
    });
    //Funcion para validar datos antes de ser enviados al controlador para guardar o actualizar un examen
    $('#formTest').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');
        let tipo = $('#tipo').val();
        if(tipo == ''){
            $('#tipo').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#tipo').closest('.form-group').addClass('has-error').focus();
        }else{
            $('#tipo').find('.text-danger').remove();
            $('#tipo').closest('.form-group').addClass('has-success').focus();
        }
        if(tipo){
			const formData = new FormData(this);
			fetch(`${_baseurl_ + _routecontroller_}/store`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.success == true){
					$('#formTest').trigger('reset');
                    $('.text-danger').remove();
                    $('.form-group').removeClass('valid').removeClass('is-valid');
                    let timeout;
                    Swal.fire({
                        title: 'Subiendo archivos y guardando información',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                                b.textContent = Swal.getTimerLeft()
                            }, 100)
                        },
                        willClose: () => {
                            clearTimeout(timeout); 
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            Swal.fire({
                                icon: 'success',
                                title: response.messages,
                                html: `<a class="btn btn-info" href="${_baseurl_ + _routecontroller_}/print/${response.test_id}" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Imprimir receta</a>`,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar',
                            }).then((result)=>{
                                result.value ? window.location.href = `${_baseurl_ + _routecontroller_}/view/${response.id}` : '';
                            });
                            $('input[name="csrf_token"]').val(response.token);
                        }
                    })
				}else if(response.success == false){
					swal.fire({
						icon: 'error',
						title: response.messages,
                        timer: 2000,
                        showConfirmButton: false
					});
					$('input[name="csrf_token"]').val(response.token);
				}
			}).catch(error => console.error(error));
            return false;
        }
    });
    //Función para ver el detalle del diagnóstico y la receta de un examen
    $(document).on('click', '.view-rp', function(e){
        e.preventDefault();
        $('.modal-body').empty();
        $('.modal-footer').empty();
        $('.modal-title').empty();
        let id = $(this).attr('value');
        fetch(`${_baseurl_ + _routecontroller_}/detail/${id}`, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(res => {
            $('.modal-title').text(`Detalles del Examen ${res.examen.nro_historia}-${res.examen.id}`);
            html = '<div class="row"><div class="col-md-12">';
            html += `<table class="table table-hover table-condensed"><thead><tr><th>DNI:</th><th style="width: 70%;">${res.examen.dni}</th><th>Fecha:</th><th style="width: 30%;">${res.examen.created_at}</th></tr></thead></table>`;
            html += `<p class="text-uppercase"><strong>Nombres y Apellidos:</strong> ${res.examen.nombres}</p>`;
            html += '<p><strong>Diagnóstico:</strong>';
            html += '<div class="col-md-12" style="float: none; margin: 0 auto;">';
            html += '<table class="table table-hover"><thead><tr><th>#</th><th>Diagnóstico</th></tr></thead><tbody>';
            $.each(res.diagnosis, function(i, value) {
                html += '<tr><td>'+ (i+1) +'</td><td>'+ value.diagnostico +'</td></tr>';
            });
            html += '</tbody></table></div>';
            html += '<p><strong>Receta:</strong>';
            html += '<div class="col-md-12"><table class="table table-hover"><thead><tr><th>#</th><th>Fármaco</th><th>Receta</th></tr></thead><tbody>';
            $.each(res.rp, function(i, value){
                html += '<tr><td>'+ (i+1) +'</td><td>'+ value.farmaco +'</td><td>'+ value.receta +'</td></tr>';
            });
            html += '</tbody></table></div></div>';
            button_pdf = `<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button><a class="btn btn-primary pull-right" href="${_baseurl_ + _routecontroller_}/print/${res.examen.id}" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Imprimir</a>`;
            $('.modal-body').append(html);
            $('.modal-footer').append(button_pdf);
            $('#modal-default').modal('show');
        });
    });
    //Función para eliminar un examen
    $(document).on('click', '.delete-test', function(e){
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
                fetch(`${_baseurl_ + _routecontroller_}/delete/${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    Swal.fire('¡Borrado!', res.messages, 'success');
                    $('input[name="csrf_token"]').val(res.token);
                    $('#test_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
    //Funcion para ver una imagen y sus detalles
    $(document).on('click', '.view-image', function(e){
        e.preventDefault();
        $('.modal-body').empty();
        $('.modal-footer').empty();
        let id = $(this).attr('value');
        fetch(`${_baseurl_ + _routecontroller_}/viewImg/${id}`, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(res => {
            $('.modal-title').text('Info del examen radiológico');
            html = `<div class="row"><div class="col-md-6"><img src="${_baseurl_ + res.image.imagen}" class="img-thumbnail center-block" width="550"></div>`;
            html += '';
            html += '<div class="col-md-6"><div class="alert alert-info alert-dismissible"><h5><i class="icon fas fa-info"></i> DATOS DEL EXAMEN RADIOLÓGICO</h5></div>';
            html += `<p><strong>Descripción del imagen radiológica:</strong> ${res.image.descripcion}</p>`;
            html += `<p><strong>Fecha del examen:</strong> ${res.image.fecha_er}</p>`;
            html += '</div></div>';
            button = `<button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>`;
            $('.modal-body').append(html);
            $('.modal-footer').append(button);
            $('#modal-default').modal('show');
        });
    });
    //Funcion para eliminar una imagen de un examen
    $(document).on('click', '.delete-image', function(e){
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
                fetch(`${_baseurl_ + _routecontroller_}/deleteImage/${id}`, {
                    method: 'DELETE'
                })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('¡Borrado!', res.messages, 'success');
                    $('input[name="csrf_token"]').val(res.token);
                    $('#images_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
    //Función para eliminar un item del diagnóstico de un examen
    $(document).on('click', '.delete-diagnosis', function(e){
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            title: '¿Estás seguro de borrar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                fetch(`${_baseurl_ + _routecontroller_}/deleteDiagnosis/${id}`, {
                    method: 'DELETE',
                })
                .then(res => res.json())
                .then(res => {
                    Swal.fire('¡Borrado!', res.messages, 'success');
                    $('input[name="csrf_token"]').val(res.token);
                    $('#diagnosis_data').DataTable().ajax.reload();
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
    //Función para eliminar un item de la receta de un examen
    $(document).on('click', '.delete-medication', function(e){
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            title: '¿Estás seguro de borrar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                fetch(`${_baseurl_ + _routecontroller_}/deleteMedication/${id}`, {
                    method: 'DELETE',
                })
                .then(res=>res.json())
                .then(res => {
                    if(res.success == true){
                        Swal.fire('¡Borrado!', res.messages, 'success');
                        $('input[name="csrf_token"]').val(res.token);
                        $('#medication_data').DataTable().ajax.reload();
                    }else{
                        Swal.fire('¡Upsss...!', res.messages, 'error');
                        $('input[name="csrf_token"]').val(res.token);
                    }
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
    //añadir campo imagen / fecha / botón remover
    $('#btn-file').on('click', function(){
        html = '<tr>';
        html += '<td><input type="file" accept="image/*" name="img[]" required></td>';
        html += '<td><input type="text" class="form-control" name="descImagen[]" required></td>';
        html += '<td><input type="date" class="form-control" name="fechaImagen[]" required></td>';
        html += '<td><button type="button" class="btn btn-danger btn-remove-info btn-xs"><span class="bi bi-x-lg"></span></button></td>';
        html += '</tr>';
        $('#tbfiles tbody').append(html);
        $('#img').val(null);
        $('#fecha').val(null);
    });
    //remover modulos filas de la tabla
    $(document).on('click','.btn-remove-info', function(){
        $(this).closest('tr').remove();
    });
    //añadir campo para examen
    $('#btn-add-test').on('click', function(){
        html = '<tr>';
        html += '<td><input type="text" class="form-control" name="exm[]" placeholder="Nombre del examen" required></td>';
        html += '<td><input type="date" class="form-control date" name="fecha[]" required></td>';
        html += '<td><input type="file" name="filePdf[]" accept="application/pdf" required class="form-control"></td>';
        html += '<td><button type="button" class="btn btn-danger remove-row-test"><i class="bi bi-x-lg"></i></button></td>';
        html += '</tr>';
        $('#tabletest tbody').append(html);
    });
    //remover modulos filas de la tabla
    $(document).on('click','.remove-row-test', function(){
        $(this).closest('tr').remove();
    });
    //Funciones para agregar diagnostico
	$('#diagnosis').autocomplete({
        source: function(request, response){
            $.ajax({
                url     : _baseurl_ + 'mtmt/diagnosticos/search',
                method  : "POST",
                dataType: "json",
                data: {q: request.term},
                success:function(data){
                    response(data.res);
                }
            });
        },
        minLength: 2,
        select: function(event, ui){
            data = ui.item.id+'*'+ui.item.cod+'*'+ui.item.descripcion+'*'+ui.item.label;
            $('#btn-add-diagnosis').val(data);
        },
    });
	//Función para agregar el diagnóstico a la lista
    $('#btn-add-diagnosis').on('click', function(){
        data = $(this).val();
        if(data != ''){
            let infodiagnosis = data.split('*');
            fetch(`${_baseurl_}mtmt/diagnosticos/validateItem/${infodiagnosis[0]}`)
            .then(res=>res.json())
            .then(res => {
                console.log(res);
                if(res.success == false){
                    infodiagnosis = data.split('*');
                    html_data = '<tr>';
                    html_data += '<td>'+ infodiagnosis[1] +'</td>';
                    html_data += '<td><input type="hidden" name="diagnosis_id[]" value="'+infodiagnosis[0]+'">'+infodiagnosis[2]+'</td>';
                    html_data += '<td><button type="button" class="btn btn-danger btn-xs btnDeleteDiagnosis" value="'+infodiagnosis[0]+'"><i class="bi bi-x-lg"></i></button></td>';
                    html_data += '</tr>';
                    $('#tableDiagnosis tbody').append(html_data);
                    $('#btn-danger').val(null);
                    $('#diagnosis').val(null);
                    $('#btn-add-diagnosis').val(null);
                    alertMessage('success', `<h5>${infodiagnosis[2]} agregado</h5>`);
                }else if(res.success == true){
                    $('#btn-danger').val(null);
                    $('#diagnosis').val(null);
                    $('#btn-add-diagnosis').val(null);
                    alertMessage('error', `<h5>Ya existe el diagnóstico en la lista</h5>`);
                }
            }).catch(function(err) {
                console.log(err);
            });
        }else{
            Swal.fire(
                '¡Vacío!',
                'Escribe algo',
                'error'
            );
        }
    });
    //remover modulos filas de la tabla de diagnósticos
    $(document).on('click','.btnDeleteDiagnosis', function(e){
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            text: '¿Estás seguro de quitarlo de la lista?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                fetch(`${_baseurl_}mtmt/diagnosticos/deleteRow/${id}`, {
                    method: 'DELETE',
                })
                .then(res=>res.json())
                .then(res => {
                    if(res.success == true){
                        $(this).closest('tr').remove();
                        $('input[name="csrf_token"]').val(res.token);
                    }
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
    //Funciones para agregar diagnostico
	$('#drugs').autocomplete({
        source: function(request, response){
            $.ajax({
                url     : `${_baseurl_}mtmt/farmacos/search`,
                method  : "POST",
                dataType: "json",
                data: {q: request.term},
                success:function(data){
                    response(data.res);
                }
            });
        },
        minLength: 2,
        select: function(event, ui){
            data = ui.item.id+'*'+ui.item.label;
            $('#btn-add-drug').val(data);
        },
    });
    //Función para agregar el diagnóstico a la lista
    $('#btn-add-drug').on('click', function(){
        data = $(this).val();
        if(data != ''){
            let infodrug = data.split('*');
            fetch(`${_baseurl_}mtmt/farmacos/validateItem/${infodrug[0]}`)
            .then(res=>res.json())
            .then(res => {
                console.log(res);
                if(res.success == false){
                    html_data = '<tr>';
                    html_data += '<td><input type="hidden" name="drug_id[]" value="'+infodrug[0]+'">'+ infodrug[1] +'</td>';
                    html_data += '<td><input type="text" class="form-control" name="rp[]" placeholder="Ingrese rp"></td>';
                    html_data += '<td><input type="number" min="0" class="form-control" name="quantity[]" placeholder="Ingrese cantidad"></td>';
                    html_data += '<td><button type="button" class="btn btn-danger btn-xs btnDeleteDrug" value="'+infodrug[0]+'"><i class="bi bi-x-lg"></i></button></td>';
                    html_data += '</tr>';
                    $('#tableDrugs tbody').append(html_data);
                    $('#btn-danger').val(null);
                    $('#drugs').val(null);
                    $('#btn-add-drug').val(null);
                    alertMessage('success', `<h5>${infodrug[1]} agregado</h5>`);
                }else if(res.success == true){
                    $('#btn-danger').val(null);
                    $('#drugs').val(null);
                    $('#btn-add-drug').val(null);
                    alertMessage('error', `<h5>Ya existe el diagnóstico en la lista</h5>`);
                }
            }).catch(function(err) {
                console.log(err);
            });
        }else{
            Swal.fire(
                '¡Vacío!',
                'Escribe algo',
                'error'
            );
        }
    });
    //remover modulos filas de la tabla de rp
    $(document).on('click','.btnDeleteDrug', function(e){
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            text: '¿Estás seguro de quitarlo de la lista?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrarlo'
        }).then((result) => {
            if (result.value) {
                fetch(`${_baseurl_}mtmt/farmacos/deleteRow/${id}`, {
                    method: 'DELETE',
                })
                .then(res=>res.json())
                .then(res => {
                    if(res.success == true){
                        $(this).closest('tr').remove();
                        $('input[name="csrf_token"]').val(res.token);
                    }
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
});

function alertMessage(type, message){
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
        
    Toast.fire({
        icon: type,
        title: message,
    });
}

function openModelPDF(id) {
    $('.modal-body').empty();
    $('.modal-footer').empty();
    $('.modal-title').empty();
    fetch(`${_baseurl_ + _routecontroller_}/viewDocument/${id}`, {
        method: 'GET'
    })
    .then(res => res.json())
    .then(res => {
        $('.modal-title').text(`Detalles del Examen ${res.document.nro_historia}-${res.document.id}`);
        html = '<iframe id="iframePDF" frameborder="0" scrolling="no" width="100%" height="500px"></iframe>';
        $('.modal-body').append(html);
        $('#iframePDF').attr('src', _baseurl_ + res.document.archivo);
        button_modal = `<a class="btn btn-primary" href="${_baseurl_ + res.document.archivo}" target="_blank"><i class="bi bi-printer"></i> Imprimir</a> <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>`;
        $('.modal-footer').append(button_modal);
        $('#modal-default').modal('show');
    });
}
//Función calular FPP y Edad Gestacional
function getFpp(dateString){
    let fumDate = new Date(dateString);
    // Calcular la fecha probable de parto (FPP)
    let fppDate = new Date(fumDate);
    fppDate.setDate(fppDate.getDate() + 280); // 280 días = 40 semanas
    // Calcular la edad gestacional actual
    let today = new Date();
    let diffTime = today - fumDate;
    let diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    let gestationalWeeks = Math.floor(diffDays / 7);
    let gestationalDays = diffDays % 7;
    // Mostrar la FPP
    $('#fpp').addClass('is-valid').val(fppDate.toLocaleDateString());
    // Mostrar la edad gestacional
    $('#eg').addClass('is-valid').val(gestationalWeeks + ' semanas (' + gestationalDays + ' días)');
}
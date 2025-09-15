let manageReportTable;
let manegeDiagnosisTable;

$(document).ready(function(){
    const historia_id   = $('#historia_id').val();
    const informe_id    = $('#informId').val();
    manageTestTable = $('#report_hcl_data').DataTable({
        'ajax': _baseurl_ + 'hcl/' + _routecontroller_ + '/list/' + historia_id,
        'order': [0, 'asc']
    });
    manegeDiagnosisTable = $('#diagnosis_data').DataTable({
        'ajax': _baseurl_ + 'hcl/' + _routecontroller_ + '/listDiagnosis/' + informe_id,
        'order': [0, 'asc']
    });
    //tabla index historias
    $('#informes').jtable({
        title       : "INFORMES CLÍNICOS",
        paging      : true,
        overflow    : scroll,
        sorting     : true,
        actions: {
            listAction: _baseurl_ + 'hcl/historias/list',
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
            historia: {
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
                title: 'VER',
                width: '6%',
                sorting:false,
                edit:false,
                create:false,
                display: (data) => {
                    return permissions.view == 1 ? `<button type="button" class="btn btn-info view-row btn-xs" value="${data.record.id}"><span class="fa fa-folder-open"></span> Ver</button> ` : ``;
                }
            },
            nuevo: {
                title: 'NUEVO',
                width: '6%',
                sorting:false,
                edit:false,
                create:false,
                display: (data) => {
                    return permissions.insert == 1 ? `<button type="button" class="btn btn-success add-new btn-xs" value="${data.record.id}"><span class="fa fa-plus"></span> Nuevo</button>` : ``
                    ;
                }
            }
        },
        recordsLoaded: (event, data) => {
            if(permissions.insert == 1){
                $('.add-new').click(function(e){
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = _baseurl_ + 'hcl/' + _routecontroller_ + `/add/${id}`;
                });
            }
            if(permissions.view == 1){
                $('.view-row').click(function(e) {
                    e.preventDefault();
                    let id = $(this).attr('value');
                    window.location.href = _baseurl_ + 'hcl/' + _routecontroller_ + `/view/${id}`;
                });
            }
        }
    });
    LoadRecordsButton = $('#LoadRecordsButton');
    LoadRecordsButton.click(function(e){
        e.preventDefault();
        console.log($('#search').val())
        $('#informes').jtable('load', {
            search: $('#search').val()
        });
    });
    LoadRecordsButton.click();
    //submit formulario formReport
    $('#formReport').off('submit').on('submit', function(e){
        e.preventDefault();
        $(".text-danger").remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');
        var antecedentes    = $('#antecedentes').val();
        var hea             = $('#hea').val();
        var ef              = $('#ef').val();
        var ec              = $('#ec').val();
        var tratamiento     = $('#tratamiento').val();
        var sugerencias     = $('#sugerencias').val();
        if(antecedentes == ''){
            $('#antecedentes').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#antecedentes').closest('.form-group').addClass('has-error');
        }else{
            $('#antecedentes').find('.text-danger').remove();
            $('#antecedentes').closest('.form-group').addClass('has-success');	  	
        }
        if(hea == ''){
            $('#hea').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#hea').closest('.form-group').addClass('has-error');
        }else{
            $('#hea').find('.text-danger').remove();
            $('#hea').closest('.form-group').addClass('has-success');	  	
        }
        if(ef == '') {
            $('#ef').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#ef').closest('.form-group').addClass('has-error');
        }else{
            $('#ef').find('.text-danger').remove();
            $('#ef').closest('.form-group').addClass('has-success');	  	
        }
        if(ec == '') {
            $('#ec').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#ec').closest('.form-group').addClass('has-error');
        }else{
            $('#ec').find('.text-danger').remove();
            $('#ec').closest('.form-group').addClass('has-success');	  	
        }
        if(tratamiento == '') {
            $('#tratamiento').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#tratamiento').closest('.form-group').addClass('has-error');
        }else{
            $('#tratamiento').find('.text-danger').remove();
            $('#tratamiento').closest('.form-group').addClass('has-success');	  	
        }
        if(sugerencias == '') {
            $('#sugerencias').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#sugerencias').closest('.form-group').addClass('has-error');
        }else{
            $('#sugerencias').find('.text-danger').remove();
            $('#sugerencias').closest('.form-group').addClass('has-success');	  	
        }
        if(antecedentes && hea && ef && ec && tratamiento && sugerencias){
            $.ajax({
                url    : _baseurl_ + 'hcl/' + _routecontroller_ + '/store',
                method : "POST",
                data   : new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function(response){
                    console.log(response);
                    if(response.success == true){
                        $("#formReport").trigger('reset');
                        // remove the error text
                        $(".text-danger").remove();
                        $('.form-group').removeClass('has-error').removeClass('has-success');
                        swal.fire({
                            icon: 'success',
                            title: response.messages,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar',
                        }).then((result)=>{
                            if(result.value){
                                window.location.href = _baseurl_ + 'hcl/' + _routecontroller_ + '/view/' + response.id; 
                            }
                        });
                        $('input[name="csrf_token"]').val(response.token);
                    }else if(response.success == false){
                        swal.fire({
                            icon: 'error',
                            title: response.messages,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Aceptar',
                        });
                        $('input[name="csrf_token"]').val(response.token);
                    }
                }
            });
            return false;
        }
    });
    //elimiar informe de un paciente por Id
    $(document).on('click', '.delete-report', function(e){
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
                const url = _baseurl_ + 'hcl/' +  _routecontroller_ + `/delete/${id}`;
                fetch(url)
                .then(res=>res.json())
                .then(res => {
                    if(res.success == true){
                        Swal.fire(
                            '¡Borrado!',
                            res.messages,
                            'success'
                        );
                        $('input[name="csrf_token"]').val(res.token);
                        $('#report_hcl_data').DataTable().ajax.reload();
                    }else{
                        Swal.fire(
                            '¡Upsss...!',
                            'Algo salió mal, recargue la página',
                            'error'
                        );
                        $('input[name="csrf_token"]').val(res.token);
                    }
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
    // eliminar registro de un diagnóstico
    $(document).on('click', '.delete-diagnosis', function(e) {
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
                const url = _baseurl_ + 'hcl/' + _routecontroller_ + `/delete_diagnosis/${id}`;
                fetch(url, {
                    method: 'DELETE'
                })
                .then(res=>res.json())
                .then(res => {
                    if(res.success == true){
                        Swal.fire(
                            '¡Borrado!',
                            res.messages,
                            'success'
                        );
                        $('input[name="csrf_token"]').val(res.token);
                        $('#diagnosis_data').DataTable().ajax.reload();
                    }else{
                        Swal.fire(
                            '¡Upsss...!',
                            'Algo salió mal, recargue la página',
                            'error'
                        );
                        $('input[name="csrf_token"]').val(res.token);
                    }
                }).catch(function(err) {
                    console.log(err);
                });
            }
        });
    });
})
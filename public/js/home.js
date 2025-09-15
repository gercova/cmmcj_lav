let manageQuotesTable;
$(document).ready(function(){

    manageQuotesTable = $("#quotes_data").DataTable({
        'ajax': `${API_URL}home/get_latest_records`,
        'order': [],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands"	:  ",",
            "sLoadingRecords"	: "Cargando...",
            "oPaginate": {
                "sFirst"	:    "Primero",
                "sLast"		:     "Último",
                "sNext"		:     "Siguiente",
                "sPrevious"	: "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }		
    });

    $(document).on('click', '.changeStatus', function(e) {
        e.preventDefault();
        let id = $(this).attr('value');
        Swal.fire({
            title: '¿Estás seguro de cambiar el estado de la cita?',
            text: 'Verfica que el paciente haya sido atendido',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar estado',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                fetch(`${API_URL}home/checkStatusPatient/${id}`)
                .then(res=>res.json())
                .then(res => {
                    console.log(res)
                    if(res.success == true){
                        Swal.fire(
                            '¡Hecho!',
                            res.messages,
                            'success'
                        );
                        $('#quotes_data').DataTable().ajax.reload();
                    }else{
                        Swal.fire(
                            '¡Upsss!',
                            res.messages,
                            'error'
                        )
                    }
                    
                }).catch(function(err) {
                    console.log(err);
                });
            }
        })
    });
});
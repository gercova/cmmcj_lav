let manageUsersTable;
$(document).ready(function(){
    const tables = {
        //Listado de ocupaciones
        users: $('#user_data').DataTable({ ajax:`${API_URL}/sys/users/list`, searching:true, bLengthChange:true, processing:true, order:[]}),
    };
    //Eliminar un registro
    DeleteHandler.initButtons([
        {
            selector: '.delete-user',
            endpoint: 'oc',
            table: tables.users
        },
    ]);
	// Mostrar nombre de archivo en el input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName); 
        // Vista previa de la imagen
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
	//formulario categoria
	$('#userForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid is-valid');	

		const formData = new FormData(this);

		const submitButton = $(this).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
        try {
			const response = await axios.post(`${API_URL}/sys/users/store`, formData);
			// console.log(response);
			if(response.status == 200 && response.data.status == true){
				$('#userForm').trigger('reset');
				$('#modalUser').modal('hide');
				$('#user_data').DataTable().ajax.reload();
				Swal.fire({
					icon: response.data.type, title: 'Operación realizada', text: response.data.message, confirmButtonColor: '#3085d6', confirmButtonText: 'Aceptar',
				}).then((result)=>{
                    if(result.value){
                        window.location.href = response.data.redirect;
                    }
                });
			}else if(response.success == false){
				swal.fire({icon: 'error', title: response.data.message, confirmButtonColor: '#3085d6', confirmButtonText: 'Aceptar'});		
			}
		} catch(error) {
			console.error(error);
		} finally {
			submitButton.prop('disabled', false).html(originalButtonText);
		}
    });
});

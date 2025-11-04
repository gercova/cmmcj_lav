let slimSelect;
if (slimSelect) slimSelect.destroy();
slimSelect = new SlimSelect({
    select: '#submodule_id',
    placeholder: 'Seleccione un módulo',
    allowDeselect: true
});

const tables = {
    //Listado de ocupaciones
    permissions: $('#permission_data').DataTable({ ajax:`${API_URL}/sys/permissions/list`, searching:true, bLengthChange:true, processing:true, order:[]}),
};
//Eliminar un registro
DeleteHandler.initButtons([
    {
        selector: '.delete-permission',
        endpoint: 'px',
        table: tables.permissions
    },
]);
//boton modal permisos
$('#btn-add-permission').click(function(e){
    e.preventDefault();
    $('#permissionForm').trigger('reset');
    $('#permissionForm')[0].reset();
    $('#id').val('');
    $('.form-group').removeClass('is-invalid is-valid');
    $('#modalPermission').modal('show');
    $('.modal-title').text('Agregar Permiso');
});
//formulario permisos
$('#permissionForm').submit(async function(e){
    e.preventDefault();
    $('.text-danger').remove();
    $('.form-group').removeClass('is-invalid is-valid');

    const formData = new FormData(this);

    const submitButton = $(this).find('button[type="submit"]');
    const originalButtonText = submitButton.html();
    submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

    try {
        const response = await axios.post(`${API_URL}/sys/permissions/store`, formData);
        if(response.status == 200 && response.data.status == true){
            console.log(response);
            $('#permissionForm').trigger('reset');
            $('#modalPermission').modal('hide');
            $('#permission_data').DataTable().ajax.reload();
            Swal.fire('Operación realizada', response.data.message, response.data.type);
        }else if(response.status == false){
            swal.fire({
                icon: response.data.type,
                title: response.data.message,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
            });
        }
    } catch(error) {
        console.error(error);
    } finally {
        submitButton.prop('disabled', false).html(originalButtonText);
    }
});
//update item permiso
$(document).on('click', '.update-row', async function(e) {
    e.preventDefault();
    const id = $(this).attr('value');
    const response = await axios.get(`${API_URL}/sys/permissions/${id}`)
    if(response.status == 200){
        console.log(response.data);
        $('.modal-title').text('Actualizar Permisos');
        $(".text-danger").remove();
        $('.form-group').removeClass('is-invalid is-valid');
        if (slimSelect) slimSelect.destroy();
        slimSelect = new SlimSelect({
            select: '#submodule_id',
            placeholder: 'Seleccione un cargo',
            allowDeselect: true
        });
        slimSelect.set(response.data.module_id);
        $('#name').val(response.data.name);
        $('#guard_name').val(response.data.guard_name);
        $('#descripcion').val(response.data.descripcion);
        $('#id').val(response.data.id);
        $('#modalPermission').modal('show');
    }
});
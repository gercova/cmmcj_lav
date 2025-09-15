$(document).ready(function(){
	$('#loginForm').submit(async function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid').removeClass('is-valid');	
       
        
        $('.text-danger').remove();
        $('.form-control').removeClass('is-invalid is-valid');
        $('#loginForm').find('text-danger').remove();

        // Deshabilitar el botón de envío
        const submitButton = $('#loginButton'); // Asegúrate de que el botón tenga el id="loginButton"
        submitButton.prop('disabled', true); // Deshabilitar el botón
        submitButton.text('Cargando...'); // Cambiar el texto del botón

        try {
            // Obtener los valores del formulario
            let username = $('#username').val();
            let password = $('#password').val();
            // Configurar el cuerpo de la solicitud
            const data = { username, password };
            // Enviar la solicitud con Axios (usando async/await)
            const response = await axios.post(`${API_URL}/login`, data);
            // Procesar la respuesta
            if (response.status === 200 && response.data.status === true) {
                $('#message').html(`<div class="alert alert-success alert-dismissible">${response.data.message}</div>`);
                // Redirigir al usuario y guardar el token
                localStorage.setItem('token', response.token);
                window.location.href = response.data.redirect;
            } else if (response.data.status === false) {
                $('#message').html(`<div class="alert alert-danger alert-dismissible">${response.data.message}</div>`);
            }
        } catch (error) {
            console.error('Error:', error);
            if (error.response && error.response.data.errors) {
                $.each(error.response.data.errors, function(key, value) {
                    let inputElement = $(document).find(`[name="${key}"]`);
                    inputElement.after(`<span class="text-danger">${value[0]}</span>`).closest('.form-control').addClass('is-invalid');
                });
            } else {
                //alertNotify('error', 'Ocurrió un error al procesar la solicitud.');
                $('#message').html('<div class="alert alert-danger alert-dismissible">Error en la solicitud. Inténtalo de nuevo.</div>');
            }
        } finally {
            // Habilitar el botón de envío nuevamente
            submitButton.prop('disabled', false);
            submitButton.text('Iniciar Sesión'); // Restaurar el texto original del botón
        }
    });
});
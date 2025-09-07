$(document).ready(function(){
    
    function sessionCurrent(){
        fetch(`${_baseurl_}sgt/usuarios/sessionCurrent`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
            redirect: 'follow'
        })
        .then(res => res.json())
        .then(res => {
            $('#profile-img').attr('src', `${_baseurl_ + res.user.profilePhoto}`)
            $('.summary-names').text(res.user.nombres);
            $('.profile').text(res.user.userC.especialidad);
            $('.user-names').text(res.user.userC.nombres);
        });
    }

    sessionCurrent();

    function getDataEnterprise(){
        fetch(`${_baseurl_}mtmt/enterprise/getImages`)
        .then(res => res.json())
        .then(res => {
            $('.brandImg').attr('src', `${_baseurl_ + res.images.logo_mini}`);
            $('.brandEnterprise').text(res.images.nombre_comercial);
        });
    }

    getDataEnterprise();

    $('.exit-system').on('click', function(e){
        e.preventDefault();
        swal.fire({
            title: '¿Quieres salir del sistema?',
            text: 'Estás seguro que quieres cerrar la sesión actual y salir del sistema',
            type: 'warning',   
            showCancelButton: true,   
            confirmButtonColor: "#16a085",   
            confirmButtonText: "Si, salir",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            animation: "slide-from-top"
        }).then((result) => {
            if (result.value) {
                window.location = _baseurl_ + 'auth/logout';
            }
        });
    });
});
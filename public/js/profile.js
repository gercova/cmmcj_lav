$(document).ready(function(){
    $(".profileImg").change(function(){
        let imagen = this.files[0];
        /*=============================================
        VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
        =============================================*/
        if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
            $(".profileImg").val("");
            swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen debe estar en formato JPG o PNG!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        }else if(imagen["size"] > 2000000){
            $(".profileImg").val("");
            swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen no debe pesar más de 2MB!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        }else{
            let datosImagen = new FileReader;
            datosImagen.readAsDataURL(imagen);
            $(datosImagen).on("load", function(event){
                let rutaImagen = event.target.result;
                $(".preview-profileImg").attr("src", rutaImagen);
            })
        }
    });

    $('#formProfile').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('has-error').removeClass('has-success');
        let celular = $('#celular').val();
        let cmp = $('#cmp').val();
        let rne = $('#rne').val();
        let especialidad = $('#especialidad').val();
        if(celular == '') {
            $('#celular').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#celular').closest('.form-group').addClass('has-error');
        }else{
            $('#celular').find('.text-danger').remove();
            $('#celular').closest('.form-group').addClass('has-success');
        }
        if(cmp == ''){
            $('#cmp').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#cmp').closest('.form-group').addClass('has-error');
        }else{
            $('#cmp').find('.text-danger').remove();
            $('#cmp').closest('.form-group').addClass('has-success');
        }
        if(rne == ''){
            $('#rne').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#rne').closest('.form-group').addClass('has-error');
        }else{
            $('#rne').find('.text-danger').remove();
            $('#rne').closest('.form-group').addClass('has-success');
        }
        if(especialidad == ''){
            $('#especialidad').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#especialidad').closest('.form-group').addClass('has-error');
        }else{
            $('#especialidad').find('.text-danger').remove();
            $('#especialidad').closest('.form-group').addClass('has-success');
        }
        if(celular && cmp && rne && especialidad){
            fetch(`${_baseurl_ + _routecontroller_}/store`, {
                method: 'POST',
                body: new FormData(this),
            })
            .then(res => res.json())
            .then(res => {
                if(res.success == true){
                    Swal.fire({
                        icon: 'success',
                        title: res.messages,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar',
                    }).then((result) => {
                        window.location.href = `${_baseurl_ + _routecontroller_}/index/${res.user}`;
                    });
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

    $('#formPassword').submit(function(e){
        e.preventDefault();
        const formPassword = new FormData(this);
        fetch(`${_baseurl_ + _routecontroller_}/store`, {
            method: 'POST',
            body: formPassword,
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);
            if(res.success == true){
                Swal.fire({
                    icon: 'success',
                    title: res.messages,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    window.location.href = `${_baseurl_ + _routecontroller_}/index/${res.user}`;
                });
                $('input[name="csrf_token"]').val(res.token);
                    
            }else if(res.success == false){
                    swal.fire({
                        icon: 'error',
                        title: res.messages,
                        timer: 2000,
                        showConfirmButton: false
                    });
                $('input[name="csrf_token"]').val(res.token);
            }
        }).catch(error => console.error(error));
        return false;
    });

    $('#formProfileImg').submit(function(e){
        e.preventDefault();
        const formProfileImg = new FormData(this);
        fetch(`${_baseurl_ + _routecontroller_}/store`, {
            method: 'POST',
            body: formProfileImg,
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);
            if(res.success == true){
                Swal.fire({
                    icon: 'success',
                    title: res.messages,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    window.location.href = `${_baseurl_ + _routecontroller_}/index/${res.user}`;
                });
                $('input[name="csrf_token"]').val(res.token);
                    
            }else if(res.success == false){
                swal.fire({
                    icon: 'error',
                    title: res.messages,
                    timer: 2000,
                    showConfirmButton: false
                });
                $('input[name="csrf_token"]').val(res.token);
            }
        }).catch(error => console.error(error));
        return false;
    });

    function loadProfileImg(){
        fetch(`${_baseurl_}sgt/usuarios/sessionCurrent`)
        .then(res => res.json())
        .then(res => {
            $('.preview-profileImg').attr("src", _baseurl_ + res.user.profilePhoto);
        })
    }

    loadProfileImg()
});
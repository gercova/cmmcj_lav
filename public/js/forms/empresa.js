$(document).ready(function(){
    //crear nuevo logo
    $(".mini-logo").change(function(){
        let imagen = this.files[0];
        /*=============================================
        VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
        =============================================*/
        if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
            $(".mini-logo").val("");
            swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen debe estar en formato JPG o PNG!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        }else if(imagen["size"] > 2000000){
            $(".mini-logo").val("");
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
                $(".preview-mini-logo").attr("src", rutaImagen);
            })
        }
    });
    //crear nuevo logo
    $(".logo").change(function(){
        let imagen = this.files[0];
        /*=============================================
        VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
        =============================================*/
        if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
            $(".logo").val("");
            swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen debe estar en formato JPG o PNG!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        }else if(imagen["size"] > 2000000){
            $(".logo").val("");
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
                $(".preview-logo").attr("src", rutaImagen);
            })
        }
    });
    //crear nuevo logo
    $(".logo-receta").change(function(){
        let imagen = this.files[0];
        /*=============================================
        VALIDAMOS EL FORMATO DE LA IMAGEN SEA JPG O PNG
        =============================================*/
        if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
            $(".logo-receta").val("");
            swal.fire({
                title: "Error al subir la imagen",
                text: "¡La imagen debe estar en formato JPG o PNG!",
                type: "error",
                confirmButtonText: "¡Cerrar!"
            });
        }else if(imagen["size"] > 2000000){
            $(".logo-receta").val("");
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
                $(".preview-logo-receta").attr("src", rutaImagen);
            })
        }
    })
    //Funcion para validar datos antes de ser enviados al controlador para guardar o actualizar un examen
    $('#formEnterprise').off('submit').on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(this);
        fetch(`${_baseurl_ + _routecontroller_}/store`, {
            method: 'POST',
            body: formData,
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
                    window.location.href = `${_baseurl_ + _routecontroller_}`;
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

    $('#formLogo').off('submit').on('submit', function(e){
        e.preventDefault();
        const formDataLogo = new FormData(this);
        fetch(`${_baseurl_ + _routecontroller_}/store`, {
            method: 'POST',
            body: formDataLogo,
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
                    window.location.href = `${_baseurl_ + _routecontroller_}`;
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

    $('#formLogoMin').off('submit').on('submit', function(e){
        e.preventDefault();
        const formDataLogoMin = new FormData(this);
        fetch(`${_baseurl_ + _routecontroller_}/store`, {
            method: 'POST',
            body: formDataLogoMin,
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
                    window.location.href = `${_baseurl_ + _routecontroller_}`;
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

    $('#formLogoBackground').submit(function(e){
        e.preventDefault();
        const formDataLogoMin = new FormData(this);
        fetch(`${_baseurl_ + _routecontroller_}/store`, {
            method: 'POST',
            body: formDataLogoMin,
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
                    window.location.href = `${_baseurl_ + _routecontroller_}`;
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

    function loadImages(){
        fetch(`${_baseurl_}mtmt/enterprise/getImages`)
        .then(res => res.json())
        .then(res => {
            $('.preview-mini-logo').attr("src", _baseurl_ + res.images.logo_mini);
            $('.preview-logo').attr("src", _baseurl_ + res.images.logo);
            $('.preview-logo-receta').attr("src", _baseurl_ + res.images.logo_receta);
        })
    }

    loadImages();
});
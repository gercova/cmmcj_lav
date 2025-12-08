//crear nuevo logo
$(".foto-representante").change(function(){
    let imagen = this.files[0];
    if(imagen["type"] != "image/jpeg" && imagen["type"] != "image/png"){
        $(".foto-representante").val("");
        swal.fire({
            title: "Error al subir la imagen",
            text: "¡La imagen debe estar en formato JPG o PNG!",
            type: "error",
            confirmButtonText: "¡Cerrar!"
        });
    }else if(imagen["size"] > 2000000){
        $(".foto-representante").val("");
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
            $(".preview-representante").attr("src", rutaImagen);
        })
    }
})
//crear nuevo logo
$('.mini-logo').change(function(){
    let imagen = this.files[0];
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

//Funcion para validar datos antes de ser enviados al controlador para guardar o actualizar un examen
$('#enterpriseForm').submit(async function(e){
    e.preventDefault();
    const formData = new FormData(this);
    const response = await axios.post(`${API_URL}/sys/enterprise/store`, formData);
    //const { status, type, message } = response.data;
    if(response.status == 200 &&  response.data.status == true){
        const res = await Swal.fire({
            icon: response.data.type,
            title: response.data.message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar',
        });

        if(res.isConfirmed){
            window.location.href = `${API_URL}/sys/enterprise`;
        };
    }else if(response.status == false){
        Swal.fire({
            icon: response.data.type,
            title: response.data.message,
            timer: 2000,
            showConfirmButton: false
        });
    }
});

$('#fotoRepresentanteForm').submit(async function(e){
    e.preventDefault();
    const formDataLogo = new FormData(this);
    const response = await axios.post(`${API_URL}/sys/enterprise/store`, formDataLogo);
    if(response.status == 200 && response.data.status == true){
        const res = await Swal.fire({
            icon: 'success',
            title: response.data.message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar',
        });

        if(res.isConfirmed){
            window.location.href = `${API_URL}/sys/enterprise`;
        };
    }else if(response.data.status == false){
        Swal.fire({
            icon: response.data.type,
            title: response.data.message,
            timer: 2000,
            showConfirmButton: false
        });
    }
});

$('#logoForm').submit(async function(e){
    e.preventDefault();
    const formDataLogo = new FormData(this);
    const response = await axios.post(`${API_URL}/sys/enterprise/store`, formDataLogo);
    if(response.status == 200 && response.data.status == true){
        //console.log(response.data);
        const res = await Swal.fire({
            icon: 'success',
            title: response.data.message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar',
        });

        if(res.isConfirmed){
            window.location.href = `${API_URL}/sys/enterprise`;
        };
    }else if(response.data.status == false){
        Swal.fire({
            icon: response.data.type,
            title: response.data.message,
            timer: 2000,
            showConfirmButton: false
        });
    }
});

$('#logoMiniForm').submit(async function(e){
    e.preventDefault();
    const formDataLogoMini = new FormData(this);
    const response = await axios.post(`${API_URL}/sys/enterprise/store`, formDataLogoMini);
    if(response.status == 200 && response.data.status == true){
        const res = await Swal.fire({
            icon: 'success',
            title: response.data.message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar',
        });

        if(res.isConfirmed){
            window.location.href = `${API_URL}/sys/enterprise`;
        };
    }else if(response.data.status == false){
        Swal.fire({
            icon: response.data.type,
            title: response.data.message,
            timer: 2000,
            showConfirmButton: false
        });
    }
});

async function loadImages(){
    try {
        const response = await axios.get(`${API_URL}/sys/enterprise/images`);
        if(response.status == 200 && response.data.status == true){
            $('.preview-representante').attr('src', response.data.foto_representante);
            $('.preview-logo').attr('src', response.data.logo);
            $('.preview-mini-logo').attr('src', response.data.logo_mini);
        };
    } catch(error) {
        console.log(error);
    }
}

loadImages();

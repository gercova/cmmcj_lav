$(document).ready(function(){
    $('.exit-system').on('click', async function(e){
        e.preventDefault();
        try {
            const result = await swal.fire({
                title: '¿Quieres salir del sistema?',
                text: '¿Estás seguro que quieres cerrar la sesión?',
                type: 'warning',   
                showCancelButton: true,   
                confirmButtonColor: "#16a085",   
                confirmButtonText: "Si, salir",
                cancelButtonText: "No, cancelar",
                closeOnConfirm: false,
                animation: "slide-from-top"
            });
            if (result.isConfirmed) {
                const response = await axios.post(`${API_URL}/logout`);
                if(response.status == 200 && response.data.status == true){
                    window.location.href = response.data.redirect;
                }  
            }
        } catch (error) {
            console.error('Error al cerrar la sesión:', error);   
        }
    });
});
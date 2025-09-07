$(document).ready(function(){
	$('#formLogin').submit(function(e){
        e.preventDefault();
        $('.text-danger').remove();
        $('.form-group').removeClass('is-invalid').removeClass('is-valid');	
        let user        = $('#userName').val();
        let password    = $('#userPass').val();
        if(user == ''){
            $('#userName').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#userName').closest('.form-group').addClass('is-invalid');
        }else{
            $('#userName').find('.text-danger').remove();
            $('#userName').closest('.form-group').addClass('is-valid');	  	
        }
        if(password == ''){
            $('#userPass').after('<p class="text-danger">Este campo es obligatorio</p>');
            $('#userPass').closest('.form-group').addClass('is-invalid');
        }else{
            $('#userPass').find('.text-danger').remove();
            $('#userPass').closest('.form-group').addClass('is-valid');	  	
        }
        
        if(user && password){
			const formData = new FormData(this);
			fetch(`${_baseurl_ +  _routecontroller_ }/login`, {
				method: 'POST',
				body: formData,
			})
			.then(res => res.json())
			.then(response => {
				console.log(response);
				if(response.login == true){
                    $('#formLogin').trigger('reset');
                    $('input[name="csrf_token"]').val(response.token);
                    window.location.href = _baseurl_ + response.route;
                }else if(response.login == false){
                    $('input[name="csrf_token"]').val(response.token);
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })  
                    Toast.fire({
                        icon: 'error',
                        title: response.messages
                    })
                }
			}).catch(error => console.error(error));
            return false; 
        }
    });
});
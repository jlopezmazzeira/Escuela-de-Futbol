$(document).ready(function(){

	if ($('#forgot_password_form').length) {
        $('#forgot_password_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                password_update: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la nueva contraseña'
                        }
                    }
                },
                password_confirmation: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la contraseña de confirmación'
                        },
                        identical: {
                            field: 'password_update',
                            message: 'Las contraseñas no coinciden'
                        }
                    }
                }
            }
        });
    }

	$('.login').click(function(){
		$('.login').fadeOut("slow",function(){
	    	$(".container-login").fadeIn();
	    	$(".close").fadeIn();
	  	});
	});

	$(".close").click(function(){
  		$(".container-login, .container-recover-password").fadeOut(800, function(){
    		$(".login").fadeIn(800);
  		});
	});

	/* Forgotten Password */
	$('#forgotten').click(function(){
  		$(".container-login").fadeOut(function(){
    		$(".container-recover-password").fadeIn();
    		$(".close").fadeIn();
  		});
	});

});

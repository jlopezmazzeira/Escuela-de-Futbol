$(document).ready(function(){
	
	if ($('#transport_form').length) {
        $('#transport_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el nombre del transportista'
                        },
                        regexp: {
                           regexp: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                       }
                    }
                },
                lastname: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el apellido del transportista'
                        },
                        regexp: {
                           regexp: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                       }
                    }
                },
                movil: {
                    validators: {
                        numeric: {
                            message: 'Solo se permiten números',
                        },
                        notEmpty: {
                            message: 'Por favor indique el teléfono del transportista'
                        }
                    }
                }
            }
        });
    }

	$('#delete_form').submit(function(){
		//serialize form data
		//var formData = $(this).serialize();
		//get form action
		var transport = $('#modalDeleteTransport').attr('transport');
		$("#modalDeleteTransport").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'transport_id' : transport};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		var message = "";
	           	if (data === 'false')
	           		message = "El transporte no puede ser eliminada, debido a que existen estudiantes asignados a dicho transporte!";	
	           	else message = "Transporte eliminado satifactoriamente!";

	           	$(".message").text(message);
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar el transporte!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
	});
	
	if ($('#license').length){
		$('#license').fileinput({
		    language: 'es',
		    uploadUrl: '#',
		    allowedFileExtensions : ['jpg', 'png','pdf'],
		});
	}

	if ($('#enrollment').length){
			$('#enrollment').fileinput({
		    language: 'es',
		    uploadUrl: '#',
		    allowedFileExtensions : ['jpg', 'png','pdf'],
		});	
	}

	if ($('#permission').length){
		$('#permission').fileinput({
		    language: 'es',
		    uploadUrl: '#',
		    allowedFileExtensions : ['jpg', 'png','pdf'],
		});	
	}

	if ($('#img-license').length){
		$('#img-license').click(function(){
	       var url = $(this).attr('file');
	       window.open(url);
	    });
	}

	if ($('#img-permission').length){
	    $('#img-permission').click(function(){
	       var url = $(this).attr('file');
	       window.open(url);
	    });
	}

    if ($('#img-enrollment').length){
	    $('#img-enrollment').click(function(){
	       var url = $(this).attr('file');
	       window.open(url);
	    });
	}
});

function deleteTransport(transport) {
	$("#modalDeleteTransport").modal('show');
	$("#modalDeleteTransport").attr("transport", transport);	
}
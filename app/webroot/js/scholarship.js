$(document).ready(function(){
	if ($('#scholarship_form').length) {
		$('#scholarship_form').bootstrapValidator({
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
	                        message: 'Por favor indique el nombre del descuento'
	                    }
	                }
	            },
	            alias: {
	                validators: {
	                    stringLength: {
	                        min: 3,
	                    },
	                    notEmpty: {
	                        message: 'Por favor indique el alias del descuento'
	                    }
	                }
	            },
	            percentage: {
	                validators: {
	                    numeric: {
	                        message: 'No es un valor númerico',
	                        // The default separators
	                        thousandsSeparator: '',
	                        decimalSeparator: '.'
	                    },
	                    notEmpty: {
	                        message: 'Por favor indique el valor númerico del descuento'
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
		var scholarship = $('#modalDeleteSchorship').attr('scholarship');
		$("#modalDeleteSchorship").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'scholarship_id' : scholarship};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		var message = "";
	           	if (data === 'false')
	           		message = "El descuento no puede ser eliminado, debido a que existen estudiantes con poseen dicho descuento!";	
	           	else message = "Descuento eliminado satifactoriamente!";
	           	$(".message").text(message);
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar el descuento!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
	});
});

function deleteSchorship(scholarship) {
	$("#modalDeleteSchorship").modal('show');
	$("#modalDeleteSchorship").attr("scholarship", scholarship);	
}
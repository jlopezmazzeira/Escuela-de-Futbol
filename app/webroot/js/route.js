$(document).ready(function(){
	if ($('#route_form').length) {
		$('#route_form').bootstrapValidator({
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
	                        message: 'Por favor indique el nombre de la ruta'
	                    }
	                }
	            },
	            cost: {
	                validators: {
	                    numeric: {
	                        message: 'No es un valor númerico',
	                        // The default separators
	                        thousandsSeparator: '',
	                        decimalSeparator: '.'
	                    },
	                    notEmpty: {
	                        message: 'Por favor indique el costo de la ruta'
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
		var route = $('#modalDeleteRoute').attr('route');
		$("#modalDeleteRoute").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'route_id' : route};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		var message = "";
	           	if (data === 'false') {
	           		message = "La ruta no puede ser eliminada, debido a que existen estudiantes asignados a dicha ruta!";	
	           	} else {
	           		message = "Ruta eliminado satifactoriamente!";
	           	}
	           	$(".message").text(message);
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar la ruta!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
	});
});

function deleteRoute(route) {
	$("#modalDeleteRoute").modal('show');
	$("#modalDeleteRoute").attr("route", route);	
}
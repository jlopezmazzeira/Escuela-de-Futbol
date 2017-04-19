$(document).ready(function(){

	if ($('#category_form').length) {
		$('#category_form').bootstrapValidator({
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
	                        message: 'Por favor indique el nombre de la categoría'
	                    }
	                }
	            },
	            age_min: {
	                validators: {
	                    integer: {
	                        message: 'No es un valor númerico'
	                    },
	                    notEmpty: {
	                        message: 'Por favor indique la edad mímina de la categoría'
	                    }
	                }
	            },
	            age_max: {
	                validators: {
	                    integer: {
	                        message: 'No es un valor númerico'
	                    },
	                    notEmpty: {
	                        message: 'Por favor indique la edad máxima de la categoría'
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
		var category = $('#modalDeleteCategory').attr('category');
		$("#modalDeleteCategory").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'category_id' : category};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	           	var message = "";
	           	if (data === 'false')
	           		message = "La categoría no puede ser eliminada, debido a que existen estudiantes asignados a dicha categoría!";	
	           	else
	           		message = "Categoría eliminado satifactoriamente!";

	           	$(".message").text(message);
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar categoría!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
	});
});

function deleteCategory(category) {
	$("#modalDeleteCategory").modal('show');
	$("#modalDeleteCategory").attr("category", category);	
}
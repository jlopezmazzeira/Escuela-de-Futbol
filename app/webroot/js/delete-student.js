$(document).ready(function() {
	$('#delete_form').submit(function(){
		//serialize form data
		//var formData = $(this).serialize();
		//get form action
		var student = $('#modalDeleteStudent').attr('student');
		$("#modalDeleteStudent").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'student_id' : student};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	            $(".message").text("Estudiante eliminado satifactoriamente!");
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar el estudiante!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
	});

});

function deleteStudent(student) {
	$("#modalDeleteStudent").modal('show');
	$("#modalDeleteStudent").attr("student", student);
}
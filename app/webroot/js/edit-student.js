$(document).ready(function() {
	//Calendar in Spanish
	$('.input-group.date').datepicker({
	    language: "es",
	    autoclose: true
	});

	var siblings_student = $("#siblings").val();
    if(siblings_student == 1){
        $('#divSiblings').fadeIn('slow');
        $('#rb_sibling_1').css('display','block');
    	$('#rb_sibling_2').css('display','none');
    	$('#sibling_1').prop('checked',true);
    	$('#sibling_2').prop('checked',false);
    } else if(siblings_student > 1){
       	$('#divSiblings').fadeIn('slow');
        $('#rb_sibling_1').css('display','none');
    	$('#rb_sibling_2').css('display','block');
    	$('#sibling_1').prop('checked',false);
    	$('#sibling_2').prop('checked',true);
    }

    if ($('#student_form').length) {
        $('#student_form').bootstrapValidator({
        message: 'This value is not valid',
          submitButton: '#submit_btn',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el nombre del estudiante'
                        },
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
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
                            message: 'Por favor indique el apellido del estudiante'
                        },
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                        }
                    }
                },
                gender: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el género del estudiante'
                        }
                    }
                },
                birthday: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha de nacimiento del estudiante'
                        }
                    }
                },
                phone: {
                    validators: {
                        regexp: {
                           regexp: /^[0-9]+$/,
                           message: 'Solo se permiten números'
                        }
                    }
                },
                home_phone: {
                    validators: {
                        regexp: {
                           regexp: /^[0-9]+$/,
                           message: 'Solo se permiten números'
                        }
                    }
                },
                email: {
                    validators: {
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'Formato incorrecto de email'
                        }
                    }
                },
                alternative_email: {
                    validators: {
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'Formato incorrecto de email'
                        }
                    }
                },
                address: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique la dirección del estudiante'
                        }
                    }
                },
                relation: {
                    validators: {
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la categoría'
                        }
                    }
                },
                training_mode: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el modo de entrenamiento'
                        }
                    }
                },
                document_type: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el tipo de documento'
                        }
                    }
                },
                document_number: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el número de documento'
                        }
                    }
                },
                responsable_name: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el nombre del responsable'
                        },
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                        }
                    }
                },
                responsable_address: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique la dirección del responsable'
                        }
                    }
                },
                responsable_phone: {
                    validators: {
                        regexp: {
                           regexp: /^[0-9\/]+$/,
                           message: 'Solo se permiten números'
                        },
                        notEmpty: {
                            message: 'Por favor indique el teléfono del responsable'
                        }
                    }
                }
            }
        });
    }

    $('#birthday_div')
      .on('changeDate show', function(e) {
          $('#student_form').bootstrapValidator('revalidateField', 'birthday');
    });

    $('#date_transport_div')
      .on('changeDate show', function(e) {
          $('#student_form').bootstrapValidator('revalidateField', 'date_transport');
    });

  if ($('input[type=radio][name=document_type]').length) {
        $('input[type=radio][name=document_type]').change(function() {
            document_type = $('input:radio[name=document_type]:checked').val();
            if (document_type == 'Pasaporte') {
                $('#document_number').attr('maxlength',20);
                $('#student_form').bootstrapValidator('addField', $('#document_number'), {
                        validators: {
                            notEmpty: {
                                message: 'Por favor indique el número de documento'
                            },
                            regexp: {
                               regexp: /^[a-zA-Z0-9]+$/,
                               message: 'Solo se permiten letras y números'
                           }
                        }
                    });
            } else if (document_type == 'Cedula'){
                $('#document_number').attr('maxlength',10);
                $('#student_form').bootstrapValidator('addField', $('#document_number'), {
                        validators: {
                            notEmpty: {
                                message: 'Por favor indique el número de documento'
                            },
                            regexp: {
                               regexp: /^[0-9]{10}$/,
                               message: 'Solo se permiten números, debe colocar 10 dígitos.'
                           }
                        }
                    });
            } else if(document_type == 'RUC'){
                $('#document_number').attr('maxlength',13);
                $('#student_form').bootstrapValidator('addField', $('#document_number'), {
                        validators: {
                            notEmpty: {
                                message: 'Por favor indique el número de documento'
                            },
                            regexp: {
                               regexp: /^[0-9]{13}$/,
                               message: 'Solo se permiten números, debe colocar 13 dígitos.'
                           }
                        }
                    });
            }
            
        });
    }

    $('#routes').change(function() {
        if ($(this).val() != "") {
            
            var formUrl = basePath + 'routes/getTransports';
            var formType = "POST";
            var formData = {'route_id' : $(this).val()};
            $.ajax({
                type: formType,
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    response = JSON.parse(data);
                    if(response.length > 0){
                        addItemsSelect(response);
                        $('#date_transport').attr('disabled',false);
                        $('#date_transport').attr('required',true);
                        $('#student_form').bootstrapValidator('addField', $('#date_transport'), {
                            validators: {
                                notEmpty: {
                                    message: 'Por favor indique la fecha de transporte'
                                }
                            }
                        });

                        $('#transport').attr('disabled',false);
                        $('#transport').selectpicker('refresh');
                        $('#transport').attr('required',true);
                        $('#student_form').bootstrapValidator('addField', $('#transport'), {
                            validators: {
                                notEmpty: {
                                    message: 'Por favor indique un transportista'
                                }
                            }
                        });
                    }
                },
                error: function(xhr,textStatus,error){
                    console.log(xhr);
                }
            });

        } else {
            $('#student_form').data('bootstrapValidator').resetField($('#date_transport'));
            $('#date_transport').removeAttr('required');
            $('#date_transport').attr('disabled',true);
            $('#date_transport').val('');

            $('#transport').val($('option:first', $('#transport')).val()).selectpicker('refresh');
            $('#student_form').data('bootstrapValidator').resetField($('#transport'));
            $('#routes')
                .on('change', function(e) {
                    $('#student_form').bootstrapValidator('revalidateField', 'transport');
                    $('#student_form').bootstrapValidator('revalidateField', 'date_transport');
            });
            $('#transport').removeAttr('required');
            $('#transport').prop('disabled', true);
            $('#transport').selectpicker('refresh');
        }
    });
    
    if($('#routes').val() == ''){
        $('#transport').prop('disabled', true);
        $('#transport').selectpicker('refresh');
        $('#date_transport').prop('disabled', true);
    } else {
        $('#date_transport').attr('required',true);
        $('#student_form').bootstrapValidator('addField', $('#date_transport'), {
            validators: {
                notEmpty: {
                    message: 'Por favor indique la fecha de transporte'
                }
            }
        });

        $('#transport').attr('required',true);
        $('#student_form').bootstrapValidator('addField', $('#transport'), {
            validators: {
                notEmpty: {
                    message: 'Por favor indique un transportista'
                }
            }
        });
    }

	$('#document_number').on('blur',function() {
		var document_number = $(this).val();
		var formUrl = basePath + 'students/getSiblings';
		var formType = "POST";
		var formData = {'document_number' : document_number};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		var obj = JSON.parse(data);
	    		var siblings = obj.Responsable.siblings;
	    		if (siblings > 0) {
		        	$('#divSiblings').fadeIn('slow');
		          	if (siblings == 1) {
		            	$('#rb_sibling_1').css('display','block');
		            	$('#rb_sibling_2').css('display','none');
		            	$('#sibling_1').prop('checked',true);
		            	$('#sibling_2').prop('checked',false);
		          	} else {
		            	$('#rb_sibling_1').css('display','none');
		            	$('#rb_sibling_2').css('display','block');
		            	$('#sibling_1').prop('checked',false);
		            	$('#sibling_2').prop('checked',true);
		          	}

		          	var document_type = obj.Responsable.document_type;
		          	if (document_type == "Pasaporte") $('#document_type_p').prop('checked',true);
		          	else if(document_type == "Cedula") $('#document_type_c').prop('checked',true);
		          	else if (document_type == "RUC") $('#document_type_r').prop('checked',true);

		          	$("#responsable_name").val(obj.Responsable.name);
                	$("#responsable_phone").val(obj.Responsable.phone);
                	$("#responsable_address").val(obj.Responsable.address);
                	$("#responsable_name").prop('readonly',true);
                	$("#responsable_phone").prop('readonly',true);
                	$("#responsable_address").prop('readonly',true);

		       	} else {
		          	$('#divSiblings').fadeOut('slow');
		          	$('#sibling_1').prop('checked',false);
		          	$('#sibling_2').prop('checked',false);
		          	$("#responsable_name").val("");
		          	$("#responsable_phone").val("");
		          	$("#responsable_address").val("");
		          	$("#responsable_name").prop('readonly',false);
		          	$("#responsable_phone").prop('readonly',false);
		          	$("#responsable_address").prop('readonly',false);
		       };
	    	},
	    	error: function(xhr,textStatus,error){
	    		console.log(xhr);
	    	}
		});

	});
    
    var category = $("#category option:selected").text();
    if (category != 'FITNESS') {
        $('#training_mode').attr('required',true);
        $('#student_form').bootstrapValidator('addField', $('#training_mode'), {
            validators: {
                notEmpty: {
                    message: 'Por favor indique el modo de entrenamiento'
                }
            }
        });
    } else if(category == 'FITNESS') {
        $("#fitness").prop('checked',true);
            $("#fitness").prop('disabled',true);
            $("#extra_training").prop('disabled',true);
            $("#fitness_s").val(1);
            $('#training_mode').prop('disabled', true);
    }

	$('#category').change(function(){
        var category = $("#category option:selected").text();
        if(category.trim() == 'FITNESS'){
            $("#fitness").prop('checked',true);
            $("#fitness").prop('disabled',true);
            $("#extra_training_s").val(0);
            $("#extra_training").prop('checked',false);
            $("#extra_training").prop('disabled',true);
            $("#fitness_s").val(1);
            $('#training_mode').removeAttr('required');
            $('#training_mode').val($('option:first', $('#training_mode')).val()).selectpicker('refresh');
            $('#student_form').data('bootstrapValidator').resetField($('#training_mode'));
            $('#training_mode').prop('disabled', true);
            $('#training_mode').selectpicker('refresh');
        } else if(category.trim() != '' && category.trim() != 'FITNESS'){
            $("#fitness_s").val(0);
            $("#fitness").prop('checked',false);
            $("#fitness").prop('disabled',false);
            $("#extra_training_s").val(0);
            $("#extra_training").prop('checked',false);
            $("#extra_training").prop('disabled',false);
            $('#training_mode').prop('disabled', false);
            $('#training_mode').selectpicker('refresh');
            $('#training_mode').attr('required',true);

            $('#student_form').bootstrapValidator('addField', $('#training_mode'), {
                validators: {
                    notEmpty: {
                        message: 'Por favor indique el modo de entrenamiento'
                    }
                }
            });

        } else {
            $("#fitness_s").val(0);
            $("#fitness").prop('checked',false);  
            $("#fitness").prop('disabled',false);
            $("#extra_training_s").val(0);
            $("#extra_training").prop('checked',false);
            $("#extra_training").prop('disabled',false);
            $('#student_form').data('bootstrapValidator').resetField($('#training_mode'));
            $('#training_mode').removeAttr('required');
        }
    });

	$('#scholarship').change(function(){
      if ($(this).val() != "") {
        var category = $("#category option:selected").text();
  		var scholarship = $("#scholarship option:selected").text();
  		$('.message').text("Ud aplicará un " +scholarship+" a la categoría "+ category.trim() +". ¿Está seguro?");
  		$('#modalScholarship').modal('show');
      }
 	});

	$('#btnCancelScholarship').click(function() {
		$('#scholarship').val($('option:first', $('#scholarship')).val());
	});

	$('#fitness').on('click', function() {
        if($(this).is(':checked')) $("#fitness_s").val(1);
        else $("#fitness_s").val(0);
    });

    $('#extra_training').on('click', function() {
        if($(this).is(':checked')) $("#extra_training_s").val(1);
        else $("#extra_training_s").val(0);
    });

    $('#submit_btn').click(function(){
      	if($('#responsableInformation').css('display') == 'none') $('#iconResponsable').trigger('click');
      	else if($('#aditionalInformation').css('display') == 'none') $('#iconInformation').trigger('click');
        else {
            var validatorObj = $('#student_form').data('bootstrapValidator');
            validatorObj.validate();
            if(validatorObj.isValid()){

                var formUrl = basePath + 'students/getDataStudent';
                var formType = "POST";
                var formData = {'student_id' : $("#student_id").val()};
                $.ajax({
                    type: formType,
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        var obj = JSON.parse(data);
                        change = false;
                        extra_training_old = obj.Student.extra_training;
                        fitness_old = obj.Student.fitness;
                        routes_transport_old = obj.Student.routes_transport_id;
                        extra_training = 0;
                        if($('#extra_training').prop('checked')) extra_training = 1;
                      
                        fitness = 0;
                        if($('#fitness').prop('checked') ) fitness = 1;
                        if (extra_training_old == 0 && extra_training == 1) change = true;
                        if (fitness_old == 0 && fitness == 1) change = true;
                        if (routes_transport_old == "" && $('#routes').val() != "" && $('transport').val() != "") change = true;

                      if (change) $('#modalConfirmation').modal('show'); 
                      else updateStudent();
                      
                    },
                    error: function(xhr,textStatus,error){
                      console.log(xhr);
                    }
                });
            }

          }
   	});

    $("#iconResponsable").click(function(event){
        event.preventDefault();
        $('#responsableInformation').toggle('slow');
        $("#iconResponsable").toggleClass("glyphicon glyphicon-chevron-down glyphicon glyphicon-chevron-right ");
    });

    $("#iconInformation").click(function(event){
        event.preventDefault();
        $('#aditionalInformation').toggle('slow');
        $("#iconInformation").toggleClass("glyphicon glyphicon-chevron-down glyphicon glyphicon-chevron-right ");
    });

    $('#btnConfirmation').click(function() {
        updateStudent();
    });

});

function updateStudent() {
    extra_training = 0;
    if($('#extra_training').prop('checked') ) extra_training = 1;
    fitness = 0;
    if($('#fitness').prop('checked') ) fitness = 1;

    document_type = $('input:radio[name=document_type]:checked').val();
    var formUrl = $("#student_form").attr('action');
    var formType = $("#student_form").attr('method');
    var formData = { 'student_id' : $("#student_id").val(),
                       'name' : $("#name").val(),
                       'lastname' : $("#lastname").val(),
                       'gender' : $("#gender").val(),
                       'birthday' : $("#birthday").val(),
                       'phone' : $("#phone").val(),
                       'home_phone' : $("#home_phone").val(),
                       'email' : $("#email").val(),
                       'alternative_email' : $("#alternative_email").val(),
                       'address' : $("#address").val(),
                       'responsable' : $("#responsable").val(),
                       'relation' : $("#relation").val(),
                       'category' : $("#category").val(),
                       'training_mode' : $("#training_mode").val(),
                       'extra_training' : extra_training,
                       'fitness' : fitness,
                       'scholarship' : $("#scholarship").val(),
                       'transport' : $('#transport').val(),
                       'routes' : $('#routes').val(),
                       'date_transport' : $("#date_transport").val(),
                       'document_number' : $("#document_number").val(),
                       'document_type' : document_type,
                       'responsable_name' : $("#responsable_name").val(),
                       'responsable_address' : $("#responsable_address").val(),
                       'responsable_phone' : $("#responsable_phone").val(),
    };
    $.ajax({
        type: formType,
        url: formUrl,
        data: formData,
        success: function(data,textStatus,xhr){
            if (data != 0) {
                var link = $('#btnMessageSuccess').attr('href');
                var new_link = link +"/"+ data + '.pdf';
                var message = 'Se ha modificado el estudiante exitosamente.' +
                               'Se han agregado nuevos items, para descargar la factura ' +
                               'presione el siguiente enlace <a href="'+new_link+'">Descargar factura</a>'
                $("#modalWait").modal('hide');
                $("#messageInscription").html(message);
                $("#modalBill").modal('show');
            } else{
                $("#modalWait").modal('hide');
                $("#messageInscription").html("Estudiante editado satifactoriamente!");
                $("#modalBill").modal('show');
            }
        },
        error: function(xhr,textStatus,error){
            $("#modalWait").modal('hide');
            $(".message").text("Ha ocurrido un error al tratar de editar el estudiante!");
            $("#modalMessage").modal('show');
        }
    }); 
        
    return false;
}

function addItemsSelect(items) {
    $("#transport").html("");
    $("#transport")
        .append('<option value="">Seleccione</option>')
        .selectpicker('refresh');

    for(var i in items){
        $("#transport")
        .append('<option value="'+items[i].t.id+'">'+items[i].t.name+' '+items[i].t.lastname+'</option>')
        .selectpicker('refresh');
    }
}
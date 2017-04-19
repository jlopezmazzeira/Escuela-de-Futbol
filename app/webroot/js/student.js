$(document).ready(function() {
	//Calendar in Spanish
	$('.input-group.date').datepicker({
	    language: "es",
	    autoclose: true
	}); 
	
	var response = "";

    $('#report').click(function() {
        ('#form_report_student').submit();
    });

    if ($('#student_form').length) {
        $('#student_form').bootstrapValidator({
        	framework: 'bootstrap',
  			message: 'This value is not valid',
        	submitButton: '#btnSubmit',
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
                responsable: {
                    validators: {
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
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
                date_inscription: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha de inscripción'
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
    
    $('#birthday_div')
        .on('changeDate show', function(e) {
            $('#student_form').bootstrapValidator('revalidateField', 'birthday');
    });

    $('#date_inscription_div')
        .on('changeDate show', function(e) {
            $('#student_form').bootstrapValidator('revalidateField', 'date_inscription');
    });

    $('#date_transport_div')
        .on('changeDate show', function(e) {
            $('#student_form').bootstrapValidator('revalidateField', 'date_transport');
    });

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
	    		var siblings = obj.People.siblings;
	    		if (siblings > 0) {
		        	$('#divSiblings').fadeIn('slow');
		          	if (siblings == 1) {
		            	$('#rb_sibling_1').css('display','block');
		            	$('#rb_sibling_2').css('display','none');
		            	$('#sibling_1').prop('checked',true);
		            	$('#sibling_2').prop('checked',false);
		          	} else{
		            	$('#rb_sibling_1').css('display','none');
		            	$('#rb_sibling_2').css('display','block');
		            	$('#sibling_1').prop('checked',false);
		            	$('#sibling_2').prop('checked',true);
		          	}

		          	var document_type = obj.People.document_type;
		          	if (document_type == "Pasaporte") $('#document_type_p').prop('checked',true); 
                    else if(document_type == "Cedula") $('#document_type_c').prop('checked',true);
		          	else if (document_type == "RUC") $('#document_type_r').prop('checked',true);
		          	
                    $("#responsable_name").val(obj.People.name);
                	$("#responsable_phone").val(obj.People.phone);
                	$("#responsable_address").val(obj.People.address);
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
		       }
	    	},
	    	error: function(xhr,textStatus,error){
	    		console.log(xhr);
	    	}
		});

	});
	
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
    
    $('#training_mode').prop('disabled', true);
    $('#training_mode').selectpicker('refresh');

    $('#transport').prop('disabled', true);
    $('#transport').selectpicker('refresh');

    $('#date_transport').prop('disabled', true);

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
		$('#scholarship').val('').selectpicker('refresh');
	});

 	$('#fitness').on('click', function() {
        if($(this).is(':checked')) $("#fitness_s").val(1);
        else $("#fitness_s").val(0);
    });

    $('#extra_training').on('click', function() {
        if($(this).is(':checked')) $("#extra_training_s").val(1);
        else $("#extra_training_s").val(0);
    });

    $('#exoneration').on('click', function() {
        if($(this).is(':checked')) $("#exoneration_s").val(1);
        else $("#exoneration_s").val(0);
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

    $('#btnSubmit').click(function(){
    	if($('#responsableInformation').css('display') == 'none') $('#iconResponsable').trigger('click');
      	else if($('#aditionalInformation').css('display') == 'none') $('#iconInformation').trigger('click');
        else {
    		var validatorObj = $('#student_form').data('bootstrapValidator');
        	validatorObj.validate();
        	if(validatorObj.isValid()){
      			$("#modalWait").modal('show');
	      		$(this).prop('disabled',true);
	      		var name = $('#name').val();
	      		var lastname = $('#lastname').val();
	      		var gender = $('#gender').val();
	      		var birthday = $('#birthday').val();
	      		var phone = $('#phone').val();
	      		var home_phone = $('#home_phone').val();
	      		var email = $('#email').val();
	      		var alternative_email = $('#alternative_email').val();
	      		var address = $('#address').val();
	      		var responsable = $('#responsable').val();
	      		var relation = $('#relation').val();
	      		var date_inscription = $('#date_inscription').val();
	      		var category = $('#category').val();
	      		var training_mode = $('#training_mode').val();
	      		var extra_training_s = $('#extra_training_s').val();
	      		var fitness_s = $('#fitness_s').val();
	      		var scholarship = $('#scholarship').val();
	      		var transport = $('#transport').val();
                var routes = $('#routes').val();
	      		var date_transport = $('#date_transport').val();
	      		var document_type = $('input:radio[name=document_type]:checked').val();
	      		var document_number = $('#document_number').val();
	      		var responsable_name = $('#responsable_name').val();
	      		var responsable_address = $('#responsable_address').val();
	      		var responsable_phone = $('#responsable_phone').val();
	      		var exoneration = $('#exoneration_s').val();

	      		var formData = {'name' : name,
								'lastname' : lastname,
								'gender' : gender,
								'birthday' : birthday,
								'phone' : phone,
								'home_phone' : home_phone,
								'email' : email,
								'alternative_email' : alternative_email,
								'address' : address,
								'responsable' : responsable,
								'relation' :  relation,
								'date_inscription' : date_inscription,
								'category' : category,
								'training_mode' : training_mode,
								'extra_training_s' : extra_training_s,
								'fitness_s' : fitness_s,
								'scholarship' : scholarship,
								'transport' : transport,
                                'routes' : routes,
								'date_transport' : date_transport,
								'document_type' : document_type,
								'document_number' : document_number,
								'responsable_name' : responsable_name,
								'responsable_address' : responsable_address,
								'responsable_phone' : responsable_phone,
								'exoneration' : exoneration
	      						};

	      		var formUrl = $('#student_form').attr('action');
				var formType = $('#student_form').attr('method');
				$.ajax({
			    	type: formType,
			   	 	url: formUrl,
			    	data: formData,
			    	success: function(data,textStatus,xhr){
			    		response = JSON.parse(data);
                        localStorage.setItem("Student", JSON.stringify(response.Student));
			    		localStorage.setItem("Responsable", JSON.stringify(response.Responsable));
			    		localStorage.setItem("Items", JSON.stringify(response.Items));
                        generateDetailBill(response.Items);
			    		var dataStudent = "";
			    		var dataResponsable = "";
			    		var name = response.Student.name +' '+ response.Student.lastname;
			    		dataStudent = '<li>Nombre Completo: ' + name + '<li>';
			    		if(response.Student.gender_id == 1) dataStudent += '<li>Género: Femenino<li>';
			    		else dataStudent += '<li>Género: Masculino<li>';
			    			
			    		dataStudent += '<li>Fecha de nacimiento: ' + response.Student.birthday + '<li>';
			    		if (response.Student.phone != "") dataStudent += '<li>Teléfono 1: ' + response.Student.phone + '<li>';
			    		if (response.Student.home_phone != "") dataStudent += '<li>Teléfono 2: ' + response.Student.home_phone + '<li>';
			    		if (response.Student.email != "") dataStudent += '<li>Email: ' + response.Student.email + '<li>';
			    		if (response.Student.alternative_email != "") dataStudent += '<li>Email alternativo: ' + response.Student.alternative_email + '<li>';
			    		if (response.Student.address != "") dataStudent += '<li>Dirección: ' + response.Student.address + '<li>';
			    		if (response.Student.responsable != "") dataStudent += '<li>Responsable: ' + response.Student.responsable + '<li>';
			    		if (response.Student.relation != "") dataStudent += '<li>Relación: ' + response.Student.relation + '<li>';
			    		if (response.Student.date_inscription != "") dataStudent += '<li>Fecha matricula: ' + response.Student.date_inscription + '<li>';
			    		$("#dataStudent").html("");
			    		$("#dataStudent").append(dataStudent);

			    		dataResponsable = '<li>Nombre Completo: ' + response.Responsable.name + '<li>';
			    		dataResponsable += '<li>Tipo de documento: ' + response.Responsable.document_type + '<li>';
			    		dataResponsable += '<li>Número de documento: ' + response.Responsable.document_number + '<li>';
			    		dataResponsable += '<li>Dirección: ' + response.Responsable.address + '<li>';
			    		dataResponsable += '<li>Teléfono: ' + response.Responsable.phone + '<li>';
			    		$("#dataResponsable").html("");
			    		$("#dataResponsable").append(dataResponsable);
			    		$("#btnSubmit").prop('disabled',false);
			    		$("#modalWait").modal('hide');
			    		$("#modalDetailBill").modal('show');
			    	},
			    	error: function(xhr,textStatus,error){
                        $("#modalWait").modal('hide');
                        $("#message").html("");
                        $("#message").text("Lo sentimos ha ocurrido un error al tratar de generar la factura.");
                        $("#modalItemFail").modal('show');
			    		console.log(textStatus);
			    		console.log(error);
			    	}
				});	
        	}
        }
   });
	
});

function generateDetailBill(items) {
    var detail = "";
    var sub_total = 0;
    var total_iva = 0;
    var total_iva_zero = 0;
    var total = 0;
    var sub_total_item_iva = 0;
    var iva = parseFloat($("#iva").val());
    var exoneration = 0;
    var scholarship = 0;
    var observation = "";

    $("#item-bill").html("");
    $("#item-pay-bill").html("");
    $("#item-title-bill").html("");
    iva = $('#iva').val();
    iva = parseFloat(iva);
    iva = iva.toFixed(2);

    if (items.length > 0) {
        for(var i in items) {
            cost_item = parseFloat(items[i].Product.cost);
            if (items[i].Product.name != 'Transporte') {
                cost_item = parseFloat(items[i].Product.cost) / (1 + (iva / 100));
                cost_item = parseFloat(cost_item);
                cost_item = cost_item.toFixed(2);
            }

            detail += '<tr>'+
                            '<td>'+ items[i].Product.name + '</td>'+
                            '<td>'+ items[i].Product.month_str + '</td>'+
                            '<td>'+ cost_item + '</td>'+
                      '</tr>';
            if(items[i].Product.name != 'Transporte'){
                //cost = items[i].Product.cost;
                exoneration_iva = 0;
                exoneration_total = 0;
                if ($("#exoneration_s").val() == 1 && items[i].Product.name == 'Matricula'){
                    observation += "Se ha exonerado costo de la matricula. ";
                    exoneration = cost_item;
                    exoneration_iva = parseFloat(items[i].Product.cost) / (1 + (iva / 100));
                    exoneration_iva = parseFloat(items[i].Product.cost) - exoneration_iva;
                    exoneration_iva = parseFloat(exoneration_iva);
                    exoneration_iva = exoneration_iva.toFixed(2);
                    exoneration_total = parseFloat(items[i].Product.cost);
                    //cost_item = 0;
                }

                scholarship_iva = 0;
                if (items[i].Product.scholarship != 0) {
                    scholarship = parseFloat(items[i].Product.scholarship);
                    scholarship = parseFloat(scholarship) / (1 + (iva / 100));
                    scholarship = scholarship.toFixed(2);
                    scholarship_iva = parseFloat(items[i].Product.scholarship) - parseFloat(scholarship);
                    scholarship_iva = parseFloat(scholarship_iva);
                    scholarship_iva = scholarship_iva.toFixed(2);
                    //cost_item = parseFloat(items[i].Product.cost) - parseFloat(scholarship);
                }

                //sub_total = parseFloat(sub_total) + parseFloat(items[i].Product.cost);
                //sub_total = parseFloat(sub_total) + parseFloat(cost_item);
                sub_total += parseFloat(cost_item);
                if (cost_item != 0){
                    total_iva += parseFloat(items[i].Product.cost) - parseFloat(cost_item) - parseFloat(scholarship_iva) - parseFloat(exoneration_iva);
                    total += parseFloat(items[i].Product.cost) - parseFloat(items[i].Product.scholarship) - parseFloat(exoneration_total);
                }
            } else {
                total_iva_zero = parseFloat(total_iva_zero) + parseFloat(items[i].Product.cost);
                total_iva_zero = total_iva_zero.toFixed(2);
            }

            observation += items[i].Product.message;
        }

        //total_iva = (parseFloat(sub_total) - parseFloat(exoneration) - parseFloat(scholarship)) * (iva / 100);
        //total_iva = Math.abs(total_iva.toFixed(2));
        //total = parseFloat(sub_total) + parseFloat(total_iva) + parseFloat(total_iva_zero) - parseFloat(exoneration) - parseFloat(scholarship);
        sub_total = sub_total.toFixed(2);
        total_iva = total_iva.toFixed(2);
        total = parseFloat(total) + parseFloat(total_iva_zero);
        total = Math.abs(total.toFixed(2));

        $("#item-bill").append(detail);
        str_iva = $('#iva').val();
        item_title_bill = '<li>Sub Total '+str_iva+'%:</li>';
        if(exoneration != 0) item_title_bill += '<li>Exoneración:</li>';
        if(scholarship != 0) item_title_bill += '<li>Descuento:</li>';
        item_title_bill += '<li>Sub Total 0%:</li>';
        item_title_bill += '<li>IVA '+str_iva+'%:</li>';
        item_title_bill += '<li>Total:</li>';
        $("#item-title-bill").html("");
        $("#item-title-bill").append(item_title_bill);

        item_pay_bill = '<li>'+ sub_total +'</li>';
        if(exoneration != 0) item_pay_bill += '<li>'+exoneration+'</li>';
        if(scholarship != 0) item_pay_bill += '<li>'+scholarship+'</li>';
        item_pay_bill += '<li>'+ total_iva_zero +'</li>';
        item_pay_bill += '<li>'+ total_iva +'</li>';
        item_pay_bill += '<li>'+ total +'</li>';
        $("#item-pay-bill").html("");
        $("#item-pay-bill").append(item_pay_bill);

        $("#observation").html("");
        $("#observation").append(observation);
    };
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
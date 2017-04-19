$(document).ready(function() {

	if ($('#provider_bill_form').length) {
        $('#provider_bill_form').bootstrapValidator({
            framework: 'bootstrap',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                bill_code: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el número de factura'
                        }
                    }
                },
                retention_number: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el número de rentención'
                        }
                    }
                },
                mode_bill: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el modo de pago'
                        }
                    }
                },
                type_payment: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique si es producto o servicio'
                        }
                    }
                },
                status_bill: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el estatus de la factura'
                        }
                    }
                },
                total_payment: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor realice el calculo del monto a pagar'
                        }
                    }
                },
                value_14: {
                	validators: {
                        numeric: {
                            message: 'No es un valor númerico',
                            // The default separators
                            thousandsSeparator: '',
                            decimalSeparator: '.'
                        }
                    }
                },
                value_0: {
                	validators: {
                        numeric: {
                            message: 'No es un valor númerico',
                            // The default separators
                            thousandsSeparator: '',
                            decimalSeparator: '.'
                        }
                    }
                }
            }
        });
    }

    if ($('#edit_provider_bill_form').length) {
        $('#edit_provider_bill_form').bootstrapValidator({
            framework: 'bootstrap',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                status_bill: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el estatus de la factura'
                        }
                    }
                }
            }
        });
    }

    if($('#li-retention').is(":visible")) {
        $('#provider_bill_form').bootstrapValidator('addField', $('#type_retention'), {
            validators: {
                notEmpty: {
                    message: 'Por favor indique el impuesto de rentención'
                }
            }
        });
    }

	$('#type_payment').change(function(){
      	provider_id = $('#provider_id').val();
      	type_payment = $(this).val();

      	var document_number = $(this).val();
		var formUrl = basePath + 'payments/getRetentions';
		var formType = "POST";
		var formData = {'provider_id' : provider_id,'type_payment_id' : type_payment};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		var obj = JSON.parse(data);
	    		$('#retention_iva').val(obj.iva);
	    		$('#retention_source').val(obj.source);
	    	},
	    	error: function(xhr,textStatus,error){
	    		console.log(xhr);
	    	}
		});
 	});

 	$('#type_retention').change(function(){
 		var type_retention = $("#type_retention option:selected").text();
		$('#retention_source').val(type_retention);
 	});

 	$('#calculate').click(function() {
 		if($('#type_payment').val() == ""){
			$('#item_fail').html("");
 			$('#item_fail').html("Debe seleccionar un tipo");
 			$('#modalTypePaymentFail').modal('show');
 		} else if($('#li-retention').is(":visible") && $('#type_retention').val == ""){
 			$('#item_fail').html("");
 			$('#item_fail').html("Debe seleccionar un porcentage de retención");
 			$('#modalTypePaymentFail').modal('show');
 		} else if($('#value_14').val() == 0 && $('#value_0').val() == 0){
 			$('#item_fail').html("");
 			$('#item_fail').html("Debe colocar un valor para poder realizar el calculo");
 			$('#modalTypePaymentFail').modal('show');
 		} else {
 			$('#sub_total').val("");
			$('#iva_c').val("");
			$('#total_retention').val("");
			$('#total_iva').val("");
			$('#total_payment').val("");

			value_14 = ($('#value_14').val() != "") ? $('#value_14').val() : 0;
			value_14 = parseFloat(value_14);
			value_14 = value_14.toFixed(2);

			value_0 = ($('#value_0').val() != "") ? $('#value_0').val() : 0;
			value_0 = parseFloat(value_0);
			value_0 = value_0.toFixed(2);

			sub_total = parseFloat(value_14) + parseFloat(value_0);
			sub_total = parseFloat(sub_total);
			sub_total = sub_total.toFixed(2);
			
			iva = parseFloat($('#iva').val());  
			iva = iva.toFixed(2);

			iva_c = parseFloat(value_14) * (iva / 100);
			iva_c = iva_c.toFixed(2);
			
			retention_source = parseFloat($('#retention_source').val());
			retention_source = retention_source.toFixed(2);
			retention_source_c = sub_total * (retention_source / 100);
			retention_source_c = retention_source_c.toFixed(2);

			retention_iva = parseFloat($('#retention_iva').val());
			retention_iva = retention_iva.toFixed(2);
			retention_iva_c = iva_c * (retention_iva / 100);
			retention_iva_c = retention_iva_c.toFixed(2);

			sub_total_c = parseFloat(value_14) + parseFloat(iva_c);
			sub_total_c = sub_total_c.toFixed(2);

			total = parseFloat(sub_total_c) - parseFloat(retention_iva_c) - parseFloat(retention_source_c);
			total = total.toFixed(2);
			$('#value_14').val(value_14);
			$('#value_0').val(value_0);
			$('#sub_total').val(sub_total);
			$('#total_iva').val(retention_iva_c);
			$('#total_retention').val(retention_source_c);
			$('#iva_c').val(iva_c);
            $('#iri').text("");
            $('#irf').text("");
            new_iri = 'IRI_'+$('#retention_iva').val()+'%:';
            new_irf = 'IRF_'+$('#retention_source').val()+'%:';
			$('#iri').text(new_iri);
            $('#irf').text(new_irf);
            $('#total_payment').val(total);
			$('#provider_bill_form').data('bootstrapValidator').resetField($('#total_payment'));
            $('#provider_bill_form').bootstrapValidator('revalidateField', 'total_payment');
 		}
 	});

 	$('#reset').click(function() {
		$('#value_14').val("");
		$('#value_0').val("");
		$('#sub_total').val("");
		$('#iva_c').val("");
		$('#total_retention').val("");
		$('#total_iva').val("");
		$('#total_payment').val("");
 	})
});
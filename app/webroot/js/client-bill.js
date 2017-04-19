var products = [];
var items = "";
var items_json = "";
var total_payment = "";
var options = "";
var payments = [];
var scholarship_bill = 0;
var scholarship_str = "";
var scholarship_total = 0;

$(document).ready(function() {

	$('#scholarship').change(function(){
  		if ($(this).val() != "") {
  			var scholarship = $("#scholarship option:selected").text();
  			$('#messageScholarchip').text("Ud aplicará un " +scholarship+". ¿Está seguro?");
  			$('#modalScholarship').modal('show');
  		} else {
			$('#scholarship').val($('option:first', $('#scholarship')).val());
			$('#scholarship').val('').selectpicker('refresh');
			scholarship_bill = 0;
			scholarship_str = "";
			generateDetailBill(items_json);
  		}
 	});

	$('#btnCancelScholarship').click(function() {
		$('#scholarship').val($('option:first', $('#scholarship')).val());
		$('#scholarship').val('').selectpicker('refresh');
	});
	
	$('#btnApplyScholarship').click(function() {
		var scholarship_id = $('#scholarship').val();
		var formUrl = basePath + 'scholarships/getScholarship';
		var formType = "POST";
		var formData = {'scholarship_id' : scholarship_id};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		scholarship_bill = parseFloat(data);
				scholarship_str = data+'%';
				generateDetailBill(items_json);
	    	},
	    	error: function(xhr,textStatus,error){
	    		console.log(xhr);
	    	}
		});
	});

	$('#client_bill_form')	
		.bootstrapValidator({
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

	$("#add").click(function() {
		var id = $('#product').val();
		var cost = $('#cost').val();
		var quantity = $('#quantity').val();
		
		if (id.trim() != "" && cost.trim() != "" && quantity.trim() != "") $("#modalItem").modal('show');
		else{
			$('#message').html('');
			$('#message').text('Debe seleccionar un producto y colocar su costo y cantidad.');
			$("#modalItemFail").modal('show');
		}
	});

	$("#addItem").click(function() {
		/*$('#client_bill_form').data('bootstrapValidator').resetField($('#quantity'));
		$('#client_bill_form').data('bootstrapValidator').resetField($('#cost'));*/
		var id = $('#product').val();
		var product_selected = $("#product option:selected").text();
		var cost = $('#cost').val();
		var description = $('#description').val();
		var quantity = $('#quantity').val();
		var iva = true;
		var sub_total_item = $('#cost').val() * $('#quantity').val();
		var product = "";

		if($('#add_iva').is(':checked')) iva = true;
        else iva = false;

		if (items_json.length > 0) {
			product_found = searchProduct(id);
			if (product_found) {
				position = getPositionProduct(id);
				cost = $('#cost').val();
				if(cost == items_json[position].cost && iva == items_json[position].iva){
					quantity = parseFloat(items_json[position].quantity) + parseFloat(quantity);  
					items_json[position].quantity = quantity;
					sub_total_item = cost * quantity;
					items_json[position].sub_total_item = sub_total_item;
					items_json[position].description = description;
					generateDetailBill(items_json);
				} else {  
					items_json[position].quantity = quantity;
					sub_total_item = cost * quantity;
					items_json[position].sub_total_item = sub_total_item;
					items_json[position].description = description;
					items_json[position].iva = iva;
					items_json[position].cost = cost;
					generateDetailBill(items_json);
				}
			} else {
				product = {"id" : id,"product" : product_selected ,"cost" : cost,"quantity" : quantity, "sub_total_item" : sub_total_item,"description" : description,"iva" : iva};
				products.push(product);
				items = JSON.stringify(products);
				items_json = JSON.parse(items);
				generateDetailBill(items_json);	
			}
		} else {
			product = {"id" : id,"product" : product_selected ,"cost" : cost,"quantity" : quantity, "sub_total_item" : sub_total_item,"description" : description,"iva" : iva};
			products.push(product);
			items = JSON.stringify(products);
			items_json = JSON.parse(items);
			generateDetailBill(items_json);
		}
	});

	$("#reset").click(function() {
		resetItem();
	});

	$("#deleteItem").click(function() {
		item = $("#modalItemDelete").attr("item");
		//x is the item delete
		products.splice(item,1);
		items = JSON.stringify(products);
		items_json = JSON.parse(items);
		generateDetailBill(items_json);
	});

	$("#btnSubmit").click(function() {
		if (items_json.length > 0) {
			var validatorObj = $('#client_bill_form').data('bootstrapValidator');
        	validatorObj.validate();
        	if(validatorObj.isValid()){
				if (Number($('#status_bill').val()) == 1) {
	        		$('#mode_bill_form')
			            .find('[name="mode_bill"]')
			                .selectpicker({
			                    // Re-validate the multiselect field when it is changed
			                    onChange: function(element, checked) {
			                        $('#receipt_form')
			                            .data('bootstrapValidator')                 // Get plugin instance
			                            .updateStatus('mode_bill', 'NOT_VALIDATED')  // Update field status
			                            .validateField('mode_bill');                 // and re-validate it
			                    }
			                })
			                .end()
			            .bootstrapValidator({
			                feedbackIcons: {
			                    valid: 'glyphicon glyphicon-ok',
			                    invalid: 'glyphicon glyphicon-remove',
			                    validating: 'glyphicon glyphicon-refresh'
			                },
			                excluded: ':disabled',
			                fields: {
			                    mode_bill: {
			                        validators: {
			                            callback: {
			                                message: 'Por indique la(s) forma(s) de pago. Dos opciones como máximo',
			                                callback: function(value, validator) {
			                                    //Get the selected options
			                                    options = validator.getFieldElements('mode_bill').val();
			                                    return (options != null && options.length >= 1 && options.length <= 2);
			                                }
			                            }
			                        }
			                    }
			                }
			            });
					$('#modalModeBill').modal('show');
				} else $('#modalBillConfirmation').modal('show');
        	}
		} else{
			$('#message').html('');
			$('#message').text('Debe al menos agregar un producto a facturar.');
			$("#modalItemFail").modal('show');
		};
	});
	
	$('#payment').click(function() {
        var validatorObj = $('#mode_bill_form').data('bootstrapValidator');
        validatorObj.validate();
        if(validatorObj.isValid()){
            $('#modes_paid').html('');
            var modes_paid = '';
            var modes_bill = ["Cheque", "Efectivo", "Deposito","Tarjeta de Credito","Transferencia", "Tarjeta de Debito"];
            for (var i = 0; i < options.length; i++) {
                position = parseInt(options[i]) - 1;
                modes_paid += '<div class="form-group">'+
                                '<label class="col-md-4 control-label">'+modes_bill[position]+'</label>'+
                                '<div class="col-md-4 inputGroupContainer">'+
                                    '<div class="input-group">'+
                                        '<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>'+
                                        '<input type="text" id="'+options[i]+'" name="'+options[i]+'" placeholder="$ 25.5" class="form-control" maxlength="6">'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="form-group">'+
                                '<label class="col-md-4 control-label">Observación '+modes_bill[position]+'</label>'+
                                '<div class="col-md-8">'+
                                    '<div class="input-group">'+
                                        '<span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>'+
                                        '<input type="text" id="observation_'+options[i]+'" name="observation_'+options[i]+'" class="form-control">'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
            }
            $('#modes_paid').append(modes_paid);
            for (var i = 0; i < options.length; i++) {
                id = options[i];
                $('#payment_form').bootstrapValidator('addField', $('#'+id), {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el monto a abonar'
                        },
                        numeric: {
                            message: 'No es un valor númerico',
                            // The default separators
                            thousandsSeparator: '',
                            decimalSeparator: '.'
                        }
                    }
                });
            }
            $('#modalPaymentConfirmation').modal('show');
        }
    });
	
	$('#btnCancelModeBill').click(function() {
		cancel();
	});

	$('#cancelPayment').click(function() {
		cancel();
	});

	$('#btnConfirmation').click(function() {
		$('#btnSubmit').prop('disabled',true);
		generateBill();
		$("#modalWait").modal('show');
	});

	$('#btnConfirmationPaid').click(function() {
        var validatorObj = $('#payment_form').data('bootstrapValidator');
        validatorObj.validate();
        if(validatorObj.isValid()){
            var amount = 0;
            payments = [];
            var total = parseFloat(total_payment);
            for (var i = 0; i < options.length; i++) {
                id = options[i];
                if ($('#'+id).val() == "") {
                    $('#message').html("");
                    $('#message').text("Debe colocar el monto a abonar en las formas de pago seleccionadas");
                    $('#modalItemFail').modal('show');
                } else {
                    amount = parseFloat(amount) + parseFloat($('#'+id).val());
                    var payment = {'mode_bill' : parseInt(id), 'amount' : parseFloat($('#'+id).val()), 'observation' : $('#observation_'+id).val()};
                    payments.push(payment);
                }
            }

            if (amount == 0) {
                $('#message').html("");
                $('#message').text("El monto a pagar no puede ser 0");
                $('#modalItemFail').modal('show');
            } else if (amount != total) {
                $('#message').html("");
                $('#message').text("El monto pagado es distinto al monto total de la factura");
                $('#modalItemFail').modal('show');
            } else {
                $('#modalPaymentConfirmation').modal('hide');
				$("#modalBillConfirmation").modal('show');      
            }
        }
    });

	$('#product').on('change',function() {
		$('#client_bill_form').bootstrapValidator('addField', $('#quantity'), {
            validators: {
                integer: {
                    message: 'Solo se permiten números'
                }
            }
        });
        $('#client_bill_form').bootstrapValidator('addField', $('#cost'), {
            validators: {
                numeric: {
                    message: 'No es un valor númerico',
                    // The default separators
                    thousandsSeparator: '',
                    decimalSeparator: '.'
                }
            }
        });

		var product = $(this).val();
		var formUrl = basePath + 'products/getCostProduct';
		var formType = "POST";
		var formData = {'product_id' : product};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	    		$("#cost").val(data);
	    		$("#quantity").val(1);
	    	},
	    	error: function(xhr,textStatus,error){
	    		console.log(xhr);
	    	}
		});
	});
});

function generateBill() {
	modes_bill = JSON.stringify(payments);
    modes_bill = JSON.parse(modes_bill);
	
	status_bill = parseInt($("#status_bill").val());
	bill_code = $("#bill_code").val();
	date_payment = $("#date_payment").val();
	
	observation = $("#observation").val();
	client_id = $("#client_id").val();
	iva = $("#iva").val();

	data_bill = {'bill_code' : bill_code, 'date_payment' : date_payment, 'modes_bill' : modes_bill, 'status_bill' : status_bill,
				'observation' : observation, 'client_id' : client_id, 'iva' : iva, 'total_payment' : total_payment,
				'scholarship_str' : scholarship_str, 'scholarship_total' : scholarship_total};
	
	products = items_json;
	var formUrl = $("#client_bill_form").attr('action');
	var formType = $("#client_bill_form").attr('method');
	var formData = {'data_bill' : data_bill, 'products' : products};

	$.ajax({
    	type: formType,
   	 	url: formUrl,
    	data: formData,
    	success: function(data,textStatus,xhr){
    		response = JSON.parse(data);
    		$("#modalWait").modal('hide');
    		var link = $("#bill_client").attr("href");
			var new_link = link+"/"+data+".pdf";
			$("#bill_client").attr("href",new_link);
			$("#bill_client").text("Descargar PDF");
			$("#modalBill").modal('show');
    	},
    	error: function(xhr,textStatus,error){
    		$("#modalWait").modal('hide');
    		$("#message").html("");
			$("#message").text("Lo sentimos ha ocurrido un error al tratar de generar la factura.");
			$("#modalItemFail").modal('show');
    		console.log(error);
    	}
	});	
    
	return false;
	
}

function generateDetailBill(items) {
	var detail = "";
	var sub_total = 0;
	var total_iva = 0;
	var total_iva_zero = 0;
	var total = 0;
	var sub_total_item_iva = 0;
	var iva = parseFloat($("#iva").val());
	$("#item-bill").html("");
	$("#item-pay-bill").html("");
	$("#item-title-bill").html("");
	if (items.length > 0) {
		for(var i in items) {
            cost_item = parseFloat(items[i].cost);
            //console.log(cost_item);
            if(items[i].iva){
            	cost_item = parseFloat(items[i].cost) / (1 + (iva / 100));
            	total += parseFloat(items[i].cost) * parseFloat(items[i].quantity);
				//sub_total = parseFloat(sub_total) + parseFloat(items[i].sub_total_item);
				//sub_total = sub_total.toFixed(2);
				cost_item = parseFloat(cost_item);
		        cost_item = cost_item.toFixed(2);
		        items[i].sub_total_item = parseFloat(cost_item) * parseFloat(items[i].quantity);
		        total_iva += (parseFloat(items[i].cost) * parseFloat(items[i].quantity)) - parseFloat(items[i].sub_total_item);
		        sub_total += parseFloat(items[i].sub_total_item);
			} else {
				total_iva_zero = parseFloat(total_iva_zero) + parseFloat(items[i].sub_total_item);
				total_iva_zero = total_iva_zero.toFixed(2);
				total += parseFloat(items[i].sub_total_item);
			}

			detail += '<tr>'+
							'<td>'+ items[i].product + '</td>'+
							'<td>'+ items[i].description + '</td>'+
							'<td>'+ cost_item + '</td>'+
							'<td>'+ items[i].quantity + '</td>'+
							'<td>'+ items[i].sub_total_item + '</td>'+
							'<td><a href="#" onclick="deleteItem('+i+')"><i class="glyphicon glyphicon-remove remove"></i></a></td>'+
					  '</tr>';
		}

		//scholarship_total = parseFloat(sub_total) * (parseFloat(scholarship_bill) / 100);
		//scholarship_total = parseFloat(scholarship_total);
		//scholarship_total = scholarship_total.toFixed(2);
		scholarship_total = parseFloat(total) * (parseFloat(scholarship_bill) / 100);
        //scholarship_total_iva = parseFloat(total) * (parseFloat(scholarship_bill) / 100);
        scholarship_total = parseFloat(scholarship_total) / (1 + (iva / 100));
        scholarship_total = scholarship_total.toFixed(2);
        scholarship_iva = parseFloat(scholarship_total) * (iva / 100);
        scholarship_iva = scholarship_iva.toFixed(2);

		//total_iva = (parseFloat(sub_total) - parseFloat(scholarship_total)) * (iva / 100);
		//total_iva = parseFloat(total_iva);
		//total_iva = total_iva.toFixed(2);
		//total = parseFloat(sub_total) + parseFloat(total_iva) + parseFloat(total_iva_zero) - parseFloat(scholarship_bill);
		//total = total.toFixed(2);
		//total_payment = total;
		sub_total = sub_total.toFixed(2);
        total_iva = parseFloat(total_iva) - parseFloat(scholarship_iva);
        total_iva = total_iva.toFixed(2);
        //total = parseFloat(total) + parseFloat(total_iva_zero) - parseFloat(scholarship_total_iva);
        total = parseFloat(total) - parseFloat(scholarship_total);
        total = Math.abs(total.toFixed(2));
        total_payment = Math.abs(total);

		$("#item-bill").append(detail);
		str_iva = $('#iva').val();
		item_title_bill = '<li>Sub Total '+str_iva+'%:</li>';
		if (scholarship_bill != 0 && total != 0) item_title_bill += '<li>Descuento ('+scholarship_str+'):</li>';
		item_title_bill += '<li>Sub Total 0%:</li>';
		item_title_bill += '<li>IVA '+str_iva+'%:</li>';
		item_title_bill += '<li>Total:</li>';
		$("#item-title-bill").html("");
		$("#item-title-bill").append(item_title_bill);

		item_pay_bill = '<li>'+ sub_total +'</li>';
		
		if (scholarship_bill != 0 && total != 0) item_pay_bill += '<li>'+scholarship_total+'</li>';
		
		item_pay_bill += '<li>'+ total_iva_zero +'</li>';
		item_pay_bill += '<li>'+ total_iva +'</li>';
		item_pay_bill += '<li>'+ total +'</li>';
		$("#item-pay-bill").html("");
    	$("#item-pay-bill").append(item_pay_bill);
	}

	resetItem();
}

function searchProduct(product_id) {
	for(var i in items_json){
		if (items_json[i].id === product_id) return true;
	}
	return false;
}

function getPositionProduct(product_id) {
	for(var i in items_json){
		if (items_json[i].id === product_id) return i;
	}
}

function deleteItem(item) {
	$("#modalItemDelete").attr("item",item);
	$("#modalItemDelete").modal('show');
}

function resetItem() {
	$('#product').val('').selectpicker('refresh');
	$('#cost').val("");
	$('#quantity').val("");
	$('#description').val("");
	$('#add_iva').prop('checked',true);
}

function cancel() {
	$('#mode_bill').selectpicker('deselectAll');
	$('#mode_bill').selectpicker('render');
	$('#mode_bill_form').data('bootstrapValidator').resetField($('#mode_bill'));
}
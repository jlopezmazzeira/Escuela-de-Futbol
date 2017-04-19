var products = [];
var items = "";
var items_json = "";
var total_payment = "";
var response = "";
var subscriber_amount = 0;
var month_paid = "";
var payments = [];
var credit_notes = 0;
var credit_note_amount = 0;
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

	$("#btnCredit").click(function() {
		if (items_json.length > 0) {
			var validatorObj = $('#student_bill_form').data('bootstrapValidator');
        	validatorObj.validate();
        	if(validatorObj.isValid()) viewModalModeBill(true);
		} else{
			$("#message").html("");
			$("#message").text("Debe al menos agregar un item a facturar.");
			$("#modalItemFail").modal('show');
		};
	});

	$("#btnCreditNote").click(function() {
		if (total_payment > 0) {
			$("#messageCreditNote").html("");
			$("#messageCreditNote").text("¿Está seguro que desea aplicar la nota de crédito?");
			$("#modalCreditNoteConfirmation").modal('show');
		} else{
			$("#message").html("");
			$("#message").text("Debe al menos agregar un item a facturar.");
			$("#modalItemFail").modal('show');
		};
	});

	$("#btnConfirmationCreditNote").click(function() {
		credit_note_amount = parseFloat($('#credit_note_amount').val());
		generateDetailBill(items_json);
		$('#btnCreditNote').attr('hidden',true);
		$('#btnCreditNoteUndo').removeAttr('hidden');

	});
	
	$("#btnCreditNoteUndo").click(function() {
		$('#btnConfirmationCreditNote').attr('hidden',true);
		$('#btnConfirmationCreditNoteUndo').removeAttr('hidden');
		$("#messageCreditNote").html("");
		$("#messageCreditNote").text("¿Está seguro que desea deshacer la nota de crédito?");
		$("#modalCreditNoteConfirmation").modal('show');
	});

	$("#btnConfirmationCreditNoteUndo").click(function() {
		credit_note_amount = 0;
		generateDetailBill(items_json);
		$('#btnCreditNoteUndo').attr('hidden',true);
		$('#btnCreditNote').removeAttr('hidden');
		$('#btnConfirmationCreditNoteUndo').attr('hidden',true);
		$('#btnConfirmationCreditNote').removeAttr('hidden');
	});

	$('#btnConfirmationCredit').click(function() {
		var item_paid_subscriber = false;
        for(var i in items_json){
            status = items_json[i].status;
            if (status == 'paid' || status == 'subscriber') item_paid_subscriber = true;
        }
        if (item_paid_subscriber) {
            $('#modalItemPaid').modal('hide');
            $('#modalBillConfirmation').modal('show');
        } else {
            $("#message").html("");
            $("#message").text("Lo sentimos al menos debe pagar o abonar un item para procesar la solicitud.");
            $("#modalItemFail").modal('show');
        }
	});

	$("#btnSubmit").click(function() {
		if (items_json.length > 0) {
			var validatorObj = $('#student_bill_form').data('bootstrapValidator');
        	validatorObj.validate();
        	if(validatorObj.isValid()){
        		for(var i in items_json){
					items_json[i].status = 'paid';
				}
				if (total_payment == 0) $('#modalBillConfirmation').modal('show');
				else viewModalModeBill(false);
			}
		} else{
			$("#message").html("");
			$("#message").text("Debe al menos agregar un item a facturar.");
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

	$('#btnConfirmationPaid').click(function() {
        var validatorObj = $('#payment_form').data('bootstrapValidator');
        validatorObj.validate();
        if(validatorObj.isValid()){
            var amount = 0;
            payments = [];
            var total = parseFloat(total_payment);
            var credit_boolean = $('#modalPaymentConfirmation').attr('credit');
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
            } else if (amount < total && credit_boolean === 'false') {
                $('#message').html("");
                $('#message').text("El monto a pagar es menor al total de la factura");
                $('#modalItemFail').modal('show');
            } else if (amount > total && credit_boolean === 'true') { 
            	$('#message').html("");
                $('#message').text("El monto a pagar es mayor al total de la factura");
                $('#modalItemFail').modal('show');
            } else {
                $('#modalPaymentConfirmation').modal('hide');
                if (credit_boolean === 'false') $("#modalBillConfirmation").modal('show');
                else processCredit(amount);
            }

            if (amount > total && credit_boolean === 'false') credit_notes = parseFloat(amount) - parseFloat(total);
        }
    });

	$('#btnCancelModeBill').click(function() {
		cancel();
	});

	$('#cancelPayment').click(function() {
		cancel();
	});

	$('#btnConfirmation').click(function() {
		$("#btnSubmit").prop('disabled',true);
		$("#btnCredit").prop('disabled',true);
		generateBill();
		$("#modalWait").modal('show');
	});

	$('#student').change(function() {
		var student_id = $(this).val();
		if (student_id.trim() == "") resetItem();
		else {
			var formUrl = basePath + 'bills/getProductStudent';
			var formType = "POST";
			var formData = {'student_id' : student_id};
			$.ajax({
		    	type: formType,
		   	 	url: formUrl,
		    	data: formData,
		    	success: function(data,textStatus,xhr){
		    		response = JSON.parse(data);
		    		addItemsSelect(response);
		    	},
		    	error: function(xhr,textStatus,error){
		    		console.log(xhr);
		    	}
			});
		}
	});

	$('#product').change(function() {
		var prefix = $('#product').val();
		if (prefix.trim() == "") {
			$('#cost').val("");
			$('#quantity').val("");
			$('#quantity').prop('disabled',false);
			$('#month').prop('disabled',false);
			$('#li_iva').attr('hidden',true);
			$('#add').prop('checked',true);
		} else {
			$('#student_bill_form').bootstrapValidator('addField', $('#quantity'), {
	            validators: {
	                integer: {
	                    message: 'Solo se permiten números'
	                }
	            }
	        });
	        
	        $('#student_bill_form').bootstrapValidator('addField', $('#cost'), {
	            validators: {
	                numeric: {
	                    message: 'No es un valor númerico',
	                    // The default separators
	                    thousandsSeparator: '',
	                    decimalSeparator: '.'
	                }
	            }
	        });
	        $('#month').val('');
			$('#month').selectpicker('render');
			$('#li_iva').attr('hidden',true);
			$('#add').prop('checked',true);
			for(var i in response) {
				if(response[i].Product.prefix == prefix) {
					$('#cost').val(response[i].Product.cost);
					$('#quantity').val(1);

					var sub_prefix = prefix.split('-');
					if (sub_prefix[1] == 'Fit' || sub_prefix[1] == 'Pen' || sub_prefix[1] == 'Trans' || sub_prefix[1] == 'ET') {
						$('#quantity').prop('disabled',true);
						$('#month').prop('disabled',false);
						var product = $("#product option:selected").text();
						getLastPaidItem(product);
					} else if(sub_prefix[1] == 'Mat') {
						$('#quantity').prop('disabled',true);
						$('#month').prop('disabled',true);
					} else {
						$('#quantity').prop('disabled',false);
						$('#month').prop('disabled',true);
						$('#li_iva').removeAttr('hidden');
					}
				}
			}
		}		
	});

	$("#add").click(function() {
		var student = $('#student').val();
		var prefix = $('#product').val();
		var cost = $('#cost').val();
		var quantity = $('#quantity').val();
		var month = $('#month').val();
		if (student.trim() != "" && prefix.trim() != "" && cost.trim() != "" && quantity.trim() != "") {
			var sub_prefix = prefix.split('-');
			if ((sub_prefix[1] == 'Fit' || sub_prefix[1] == 'Pen' || sub_prefix[1] == 'Trans' || sub_prefix[1] == 'ET') && (month === 0 || month == null)) {
				$("#message").html("");
				$("#message").text("Para poder añadir un estudiante debe llenar todos los campos.");
				$("#modalItemFail").modal('show');			
			} else $("#modalItem").modal('show');
		} else{
			$("#message").html("");
			$("#message").text("Para poder añadir un estudiante debe llenar todos los campos.");
			$("#modalItemFail").modal('show');
		}
	});

	$("#reset").click(function() {
		resetItem();
	});

	$("#addItem").click(function() {
		var student_id = $('#student').val();
		var student_selected = $("#student option:selected").text();
		var prefix = $('#product').val();
		var sub_prefix = prefix.split('-');
		var product_selected = $("#product option:selected").text();
		var cost = $('#cost').val();
		var quantity = $('#quantity').val();
		var month = $('#month').val();
		var month_selected = "";
		var year = "";
		var iva_boolean = true;
        var iva = $('#iva').val();
            iva = parseFloat(iva);
            iva = iva.toFixed(2);

		if($('#add_iva').is(':checked')) iva_boolean = true;
		else {
        	iva_boolean = false;
        	iva = 0;
        }

		var date = new Date();
		if (Number(month) !== 0) {
			month_selected = $("#month option:selected").text();
			year = date.getFullYear();
			current_month = parseInt(date.getMonth());
			if (Number(current_month) >= 9 && Number(month) < Number(current_month)) year = parseFloat(year) + 1;
		}
		
		var description = "";
		var sub_total_item = cost * quantity;
			sub_total_item = parseFloat(sub_total_item);
			sub_total_item = sub_total_item.toFixed(2);
		for(var i in response) {
			if (response[i].prefix == prefix) description = response[i].description;
		}

		if(items_json.length > 0){
			product_found = searchProduct(product_selected,student_id);
			if (product_found) {
				if (sub_prefix[1].trim() != 'Fit' && sub_prefix[1].trim() != 'Pen' && sub_prefix[1].trim() != 'ET' 
				 	&& sub_prefix[1].trim() != 'Trans' && sub_prefix[1].trim() != 'Mat') {
					position = getPositionProduct(product_selected,student_id);
					if (items_json[position].cost == cost) {
						quantity = parseFloat(items_json[position].quantity) + parseFloat(quantity);  
						items_json[position].quantity = quantity;
						sub_total_item = cost * quantity;
						sub_total_item = parseFloat(sub_total_item);
						sub_total_item = sub_total_item.toFixed(2);
						items_json[position].sub_total_item = sub_total_item;
						generateDetailBill(items_json);
					} else{
						items_json[position].quantity = quantity;
						items_json[position].cost = cost;
						sub_total_item = cost * quantity;
						sub_total_item = parseFloat(sub_total_item);
						sub_total_item = sub_total_item.toFixed(2);
						items_json[position].sub_total_item = sub_total_item;
						generateDetailBill(items_json);
					}
				} else {
					product_found_month = searchProductMonth(product_selected,student_id,month);
					month_search = searchMonthPaid(product_selected,student_id,month,month_paid);
					if (product_found_month) {
						$("#message").html("");
						$("#message").text("El producto ya ha sido agregado con dicho mes.");
						$("#modalItemFail").modal('show');	
					} else if(!month_search) { 
						$("#message").html("");
						$("#message").text("Para agregar este mes, debe cancelar el mes anterior.");
						$("#modalItemFail").modal('show');
					} else {
						item_add = {"student_id" : student_id,"student" : student_selected ,
						   "cost" : cost,"quantity" : quantity, "prefix" : prefix, "product" : product_selected,
						   "sub_total_item" : sub_total_item,"description" : description, "sub_prefix" : sub_prefix[1],
						   "month" : month, "month_selected" : month_selected, "year" : year, "status" : 'pending', 
						   'exoneration' : 0, 'iva_boolean' : iva_boolean, 'iva' : iva, checked : false};
						products.push(item_add);
						items = JSON.stringify(products);
						items_json = JSON.parse(items);
						generateDetailBill(items_json);
					}
				}
			} else {
				if (sub_prefix[1].trim() != 'Fit' && sub_prefix[1].trim() != 'Pen' && sub_prefix[1].trim() != 'ET' 
				 	&& sub_prefix[1].trim() != 'Trans') {
						item_add = {"student_id" : student_id,"student" : student_selected ,
						   "cost" : cost,"quantity" : quantity, "prefix" : prefix, "product" : product_selected,
						   "sub_total_item" : sub_total_item,"description" : description, "sub_prefix" : sub_prefix[1],
						   "month" : month, "month_selected" : month_selected, "year" : year, "status" : 'pending', 
						   'exoneration' : 0, 'iva_boolean' : iva_boolean, 'iva' : iva, checked : false};
						products.push(item_add);
						items = JSON.stringify(products);
						items_json = JSON.parse(items);
						generateDetailBill(items_json);
				} else if (sub_prefix[1].trim() === 'Fit' || sub_prefix[1].trim() === 'Pen' || sub_prefix[1].trim() === 'ET' 
				 	|| sub_prefix[1].trim() === 'Trans') {
					month_search = searchMonthPaid(product_selected,student_id,month,month_paid);
					if(!month_search) { 
						$("#message").html("");
						$("#message").text("Para agregar este mes, debe cancelar el mes anterior.");
						$("#modalItemFail").modal('show');
					} else {
						item_add = {"student_id" : student_id,"student" : student_selected ,
						   "cost" : cost,"quantity" : quantity, "prefix" : prefix, "product" : product_selected,
						   "sub_total_item" : sub_total_item,"description" : description, "sub_prefix" : sub_prefix[1],
						   "month" : month, "month_selected" : month_selected, "year" : year, "status" : 'pending', 
						   'exoneration' : 0, 'iva_boolean' : iva_boolean, 'iva' : iva, checked : false};
						products.push(item_add);
						items = JSON.stringify(products);
						items_json = JSON.parse(items);
						generateDetailBill(items_json);
					}
				}
			}
		} else {
			if (Number(month) !== 0) {
				month_search = searchMonthPaid(product_selected,student_id,month,month_paid);
				if(!month_search){
					$("#message").html("");
					$("#message").text("Para agregar este mes, debe cancelar el mes anterior.");
					$("#modalItemFail").modal('show');
				} else {
					item_add = {"student_id" : student_id,"student" : student_selected ,
				   		"cost" : cost,"quantity" : quantity, "prefix" : prefix, "product" : product_selected,
				   		"sub_total_item" : sub_total_item,"description" : description, "sub_prefix" : sub_prefix[1],
				   		"month" : month, "month_selected" : month_selected, "year" : year, "status" : 'pending', 
				   		'exoneration' : 0, 'iva_boolean' : iva_boolean, 'iva' : iva, checked : false};
					products.push(item_add);
					items = JSON.stringify(products);
					items_json = JSON.parse(items);
					generateDetailBill(items_json);	
				}
			} else {
				item_add = {"student_id" : student_id,"student" : student_selected ,
				   "cost" : cost,"quantity" : quantity, "prefix" : prefix, "product" : product_selected,
				   "sub_total_item" : sub_total_item,"description" : description, "sub_prefix" : sub_prefix[1],
				   "month" : month, "month_selected" : month_selected, "year" : year, "status" : 'pending', 
				   'exoneration' : 0, 'iva_boolean' : iva_boolean, 'iva' : iva};
				products.push(item_add);
				items = JSON.stringify(products);
				items_json = JSON.parse(items);
				generateDetailBill(items_json);
			}
		}
		
	});
	
	$("#deleteItem").click(function() {
		item = $("#modalItemDelete").attr("item");
		//x is the item delete
		products.splice(item,1);
		items = JSON.stringify(products);
		items_json = JSON.parse(items);
		generateDetailBill(items_json);
	});

});

function generateDetailBill(items_new) {
	var detail = "";
	var sub_total = 0;
	var total_iva = 0;
	var total_iva_zero = 0;
	var total = 0;
	var sub_total_item_iva = 0;
	var exoneration = 0;
	var exoneration_iva = 0;
	var iva = parseFloat($("#iva").val());
		iva = iva.toFixed(2);
	$("#item-bill").html("");
	$("#item-pay-bill").html("");
	$("#item-title-bill").html("");

	if (items_new.length > 0) {
		for(var i in items_new) {
			cost_item = parseFloat(items_new[i].cost);
			if (items_new[i].iva_boolean){
				cost_item = parseFloat(items_new[i].cost) / (1 + (iva / 100));
            	total_iva += parseFloat(items_new[i].cost) - parseFloat(cost_item);
            	total += parseFloat(items_new[i].cost) * parseFloat(items_new[i].quantity);
				//sub_total = parseFloat(sub_total) + parseFloat(items[i].sub_total_item);
				//sub_total = sub_total.toFixed(2);
				cost_item = parseFloat(cost_item);
		        cost_item = cost_item.toFixed(2);
		        items_new[i].sub_total_item = parseFloat(cost_item) * parseFloat(items_new[i].quantity);
		        sub_total += parseFloat(items_new[i].sub_total_item);
			}

			if(items_new[i].sub_prefix == 'Mat'){
				checked = '<input type="checkbox" id="exoneration_'+i+'" class="checkbox" onclick="exonerarItem('+i+')"> Exonerar';
				if (items_new[i].checked)
					checked = '<input type="checkbox" id="exoneration_'+i+'" class="checkbox" onclick="exonerarItem('+i+')" checked> Exonerar';
				else 
					checked = '<input type="checkbox" id="exoneration_'+i+'" class="checkbox" onclick="exonerarItem('+i+')"> Exonerar';

				detail += '<tr>'+
							'<td>'+ items_new[i].student + '</td>'+
							'<td>'+ items_new[i].product + '</td>'+
							'<td>'+ items_new[i].month_selected + '</td>'+
							'<td>'+ cost_item + '</td>'+
							'<td>'+ items_new[i].quantity + '</td>'+
							'<td>'+ items_new[i].sub_total_item + '</td>'+
							'<td>'+
								'<a href="#" onclick="deleteItem('+i+')"><i class="glyphicon glyphicon-remove remove"></i></a>'+
								'<label class="checkbox check-exoneration">'+
				          			checked+
				     			'</label>'+
				     		'</td>'+
					  '</tr>';
			} else {
				detail += '<tr>'+
							'<td>'+ items_new[i].student + '</td>'+
							'<td>'+ items_new[i].product + '</td>'+
							'<td>'+ items_new[i].month_selected + '</td>'+
							'<td>'+ cost_item + '</td>'+
							'<td>'+ items_new[i].quantity + '</td>'+
							'<td>'+ items_new[i].sub_total_item + '</td>'+
							'<td>'+
								'<a href="#" onclick="deleteItem('+i+')"><i class="glyphicon glyphicon-remove remove"></i></a>'+
				     		'</td>'+
					  '</tr>';
			}

			if(items_new[i].sub_prefix != 'Trans'){

				if (items_new[i].sub_prefix == 'Mat' && parseFloat(items_new[i].exoneration) != 0){
					exoneration += parseFloat(items_new[i].exoneration);
					//if (items_new[i].iva_boolean){
						//if (items_new[i].checked)
						exoneration_cal = parseFloat(items_new[i].exoneration) * (iva / 100);
						exoneration_cal = parseFloat(exoneration_cal);
						exoneration_cal = exoneration_cal.toFixed(2);
						console.log(i+"->"+exoneration_cal);
						exoneration_iva += parseFloat(exoneration_cal);
						//exoneration_iva += exoneration_cal.toFixed(2);
						//console.log(exoneration_iva);
					//}
				}
				
				if (!items_new[i].iva_boolean){
					total_iva_zero = parseFloat(total_iva_zero) + parseFloat(items_new[i].sub_total_item);
					total_iva_zero = total_iva_zero.toFixed(2);
					total += parseFloat(total_iva_zero);	
				}

				/*if (items_new[i].iva_boolean) {
					sub_total = parseFloat(sub_total) + parseFloat(items_new[i].sub_total_item);
					sub_total = sub_total.toFixed(2);
				} else {
					total_iva_zero = parseFloat(total_iva_zero) + parseFloat(items_new[i].sub_total_item);
					total_iva_zero = total_iva_zero.toFixed(2);
				}*/

			} else {
				total_iva_zero = parseFloat(total_iva_zero) + parseFloat(items_new[i].sub_total_item);
				total_iva_zero = total_iva_zero.toFixed(2);
				total += parseFloat(total_iva_zero);
			}

			sub_total_credit = 0;
			if (sub_total != 0) sub_total_credit = parseFloat(sub_total) - parseFloat(credit_note_amount);
			else total_iva_zero = parseFloat(total_iva_zero) - parseFloat(credit_note_amount);
			
		}

		//scholarship_total = parseFloat(sub_total) * (parseFloat(scholarship_bill) / 100);
		//scholarship_total = parseFloat(scholarship_total);
		//scholarship_total = scholarship_total.toFixed(2);
		scholarship_total = parseFloat(total) * (parseFloat(scholarship_bill) / 100);
        scholarship_total_iva = parseFloat(total) * (parseFloat(scholarship_bill) / 100);
        scholarship_total = parseFloat(scholarship_total) / (1 + (iva / 100));
        scholarship_total = scholarship_total.toFixed(2);
        scholarship_iva = parseFloat(scholarship_total) * (iva / 100);
        scholarship_iva = scholarship_iva.toFixed(2);

		//total_iva = (parseFloat(sub_total_credit) - parseFloat(exoneration) - parseFloat(scholarship_total)) * (iva / 100);
		//total_iva = parseFloat(total_iva);
		//total_iva = total_iva.toFixed(2);
		//total = parseFloat(sub_total_credit) + parseFloat(total_iva) + parseFloat(total_iva_zero) - parseFloat(exoneration) - parseFloat(scholarship_total);
		//total = total.toFixed(2);
		//total_payment = total;
		sub_total = sub_total.toFixed(2);
		exoneration_iva = parseFloat(exoneration_iva);
		exoneration_iva = exoneration_iva.toFixed(2);
        //console.log(total_iva);
        total_iva = parseFloat(total_iva) - parseFloat(scholarship_iva) - parseFloat(exoneration_iva);
        total_iva = total_iva.toFixed(2);
        //total = parseFloat(total) + parseFloat(total_iva_zero) - parseFloat(scholarship_total_iva);
        //console.log(total);
        //console.log(scholarship_total_iva);
        //console.log(exoneration);
        total = parseFloat(total) - parseFloat(scholarship_total_iva) - parseFloat(exoneration) - parseFloat(exoneration_iva);
        total = Math.abs(total.toFixed(2));
        total_payment = Math.abs(total);

		$("#item-bill").append(detail);
		str_iva = $('#iva').val();
		item_title_bill = '<li>Sub Total '+str_iva+'%:</li>';
		if (scholarship_bill != 0 && total != 0) item_title_bill += '<li>Descuento ('+scholarship_str+'):</li>';
		item_title_bill += '<li>Sub Total 0%:</li>';
		if(exoneration != 0) item_title_bill += '<li>Exoneración:</li>';
		if(credit_note_amount != 0) item_title_bill += '<li>Descuento:</li>';
		item_title_bill += '<li>IVA '+str_iva+'%:</li>';
		item_title_bill += '<li>Total:</li>';
		$("#item-title-bill").html("");
		$("#item-title-bill").append(item_title_bill);

		item_pay_bill = '<li>'+ sub_total +'</li>';
		
		if (scholarship_bill != 0 && total != 0) item_pay_bill += '<li>'+scholarship_total+'</li>';

		item_pay_bill += '<li>'+ total_iva_zero +'</li>';
		if(exoneration != 0) item_pay_bill += '<li>'+ exoneration +'</li>';
		if(credit_note_amount != 0) item_pay_bill += '<li>'+ credit_note_amount +'</li>';
		item_pay_bill += '<li>'+ total_iva +'</li>';
		item_pay_bill += '<li>'+ total +'</li>';
		$("#item-pay-bill").html("");
    	$("#item-pay-bill").append(item_pay_bill);

    	/*if (credit_note_amount > 0) {
    		$('#observation').html("");
    		$('#observation').text("Se ha aplicado una nota de crédito por el monto de " +credit_note_amount+".");
    	} else {
    		$('#observation').html("");
    	}*/
    	
	};

	resetItem();
	if (total_payment == 0) $('#btnCredit').prop('disabled',true);
	else $('#btnCredit').prop('disabled',false);
}

function addItemsSelect(items) {
	$("#product").html("");
	$("#product")
	    .append('<option value="">Seleccione</option>')
	    .selectpicker('refresh');

	for(var i in items){
		$("#product")
	    .append('<option value="'+items[i].Product.prefix+'">'+items[i].Product.name+'</option>')
	    .selectpicker('refresh');
	}
}

function searchProduct(product_selected,student_id) {
	for(var i in items_json){
		if (items_json[i].product === product_selected && items_json[i].student_id === student_id) return true;
	}
	return false;
}

function searchProductMonth(product_selected,student_id,month) {
	for(var i in items_json){
		if (items_json[i].product === product_selected && items_json[i].student_id === student_id 
			&& items_json[i].month === month) return true;
	}
	return false;
}

function searchMonthPaid(product_selected,student_id,month_selected,month_paid) {
	if (Number(month_selected) == Number(month_paid)) return true;
	else if (Number(month_selected) > Number(month_paid)) {
		month_previus = parseInt(month_selected) - 1;
		for(var i in items_json){
			if (items_json[i].product === product_selected && items_json[i].student_id === student_id 
			&& Number(items_json[i].month) === Number(month_previus)) return true;
		}
		return false;
	} else if (Number(month_selected) < Number(month_paid)) {
		month_search = "";
		if (Number(month_selected) == 1 && Number(month_paid) == 12) month_search = 12;
		if (Number(month_selected) > 1 && Number(month_paid) == 12) month_search = parseInt(month_selected) - 1;
		
		if (Number(month_selected) > 1 && Number(month_paid) < 12) month_search = parseInt(month_selected) - 1;
		if (Number(month_selected) == 1 && Number(month_paid) < 12) month_search = 12; 

		for(var i in items_json){
			if (items_json[i].product === product_selected && items_json[i].student_id === student_id 
			&& Number(items_json[i].month) === Number(month_search)) return true;
		}
		return false;
	}
}

function getPositionProduct(product_selected,student_id) {
	for(var i in items_json){
		if (items_json[i].product === product_selected && items_json[i].student_id === student_id)
			return i;
	}
}

function resetItem() {
	$('#student').val('').selectpicker('refresh');
	$("#product").html("");
	$("#product")
	    .append('<option value="">Seleccione</option>')
	    .selectpicker('refresh');
	$('#month').val('');
	$('#month').selectpicker('render');
	$('#month').prop('disabled',false);
	$('#cost').val("");
	$('#quantity').val("");
	$('#li_iva').attr('hidden',true);
	$('#add').prop('checked',true);
}

function deleteItem(item) {
	$("#modalItemDelete").attr("item",item);
	$("#modalItemDelete").modal('show');
}

function paidItem(item) {
	var cost = 0;
	var pending = 0;
	if(items_json[item].sub_prefix != 'Trans'){
		iva = $('#iva').val();
		iva = parseFloat(iva);
		iva = iva.toFixed(2);
		cost_item_iva = 0;
		cost_item_iva = parseFloat(items_json[item].sub_total_item) * (iva / 100);
		cost = parseFloat(items_json[item].sub_total_item) + parseFloat(cost_item_iva);
		cost = cost.toFixed(2);
		pending = cost;
	} else {
		cost = items_json[item].sub_total_item;
		pending = cost;
	}

	if($('#item_'+item).is(':checked') ){
		
		if (Number(subscriber_amount) > 0) {
			pending = subscriber_amount - cost;
			pending = parseFloat(pending);
			pending = pending.toFixed(2);
			subscriber = 0;

			subscriber_amount = subscriber_amount - cost;
			subscriber_amount = parseFloat(subscriber_amount);
			subscriber_amount = subscriber_amount.toFixed(2);
			if (subscriber_amount < 0) subscriber_amount = 0;
			if (pending >= 0 ) {
				pending = 0;
				subscriber = cost;
				items_json[item].status = 'paid';
				items_json[item].paid = cost;
			} else {
				subscriber = parseFloat(cost) + parseFloat(pending);
				subscriber = subscriber.toFixed(2);
				items_json[item].status = 'subscriber';
				items_json[item].paid = subscriber;
			}
			$('#item_subscriber_'+item).html('');
			$('#item_subscriber_'+item).text(subscriber);

			$('#item_pending_'+item).html('');
			$('#item_pending_'+item).text(pending);
		} else {
			$('#item_'+item).prop('checked',false);
			$("#message").html("");
			$("#message").text("Lo sentimos ya no puede seguir abanando a más items.");
			$("#modalItemFail").modal('show');
		} 

    } else {
    	subscriber = $('#item_subscriber_'+item).text();
    	subscriber_amount = parseFloat(subscriber) + parseFloat(subscriber_amount);
		subscriber_amount = parseFloat(subscriber_amount);
		subscriber_amount = subscriber_amount.toFixed(2);
		$('#item_pending_'+item).html('');
		$('#item_pending_'+item).text(cost);
		$('#item_subscriber_'+item).html('');
		$('#item_subscriber_'+item).text(0);
		items_json[item].paid = 0;
		items_json[item].status = 'pending';
		for(var i in items_json){

			if (items_json[i].status == 'subscriber') {
				if(items_json[i].sub_prefix != 'Trans'){
					iva = $('#iva').val();
					iva = parseFloat(iva);
					iva = iva.toFixed(2);
					cost_item_iva = 0;
					cost_item_iva = parseFloat(items_json[i].sub_total_item) * (iva / 100);
					cost = parseFloat(items_json[i].sub_total_item) + parseFloat(cost_item_iva);
					cost = cost.toFixed(2);
				} else cost = items_json[i].sub_total_item;

				subscriber = $('#item_subscriber_'+i).text();
				subscriber_amount = parseFloat(subscriber) + parseFloat(subscriber_amount);
				pending = parseFloat(subscriber_amount) - parseFloat(cost);
				pending = parseFloat(pending);
				pending = pending.toFixed(2);

				subscriber_amount = parseFloat(subscriber_amount) - parseFloat(cost);
				subscriber_amount = parseFloat(subscriber_amount);
				subscriber_amount = subscriber_amount.toFixed(2);

				if (pending >= 0 ) {
					pending = 0;
					subscriber = cost;
					items_json[i].status = 'paid';
					items_json[i].paid = cost;
				} else {
					subscriber = parseFloat(cost) + parseFloat(pending);
					subscriber = subscriber.toFixed(2);
					items_json[i].status = 'subscriber';
					items_json[item].paid = subscriber;
				}
				$('#item_subscriber_'+i).html('');
				$('#item_subscriber_'+i).text(subscriber);
				$('#item_pending_'+i).html('');
				$('#item_pending_'+i).text(pending);
			}
		}
    }
}

function generateBill() {
	credit = ""; 
	if ($("#subscriber_amount").val() != "") credit = parseFloat($("#subscriber_amount").val());

	modes_bill = JSON.stringify(payments);
    modes_bill = JSON.parse(modes_bill);

	item = {'credit' :credit ,
			'bill_code' : $("#bill_code").val(),
			'date_payment' : $("#date_payment").val(),
			'modes_bill' : modes_bill,
			'observation' : $("#observation").val(),
			'responsable' : parseInt($("#responsable_id").val()),
			'total' : parseFloat(total_payment),
			'credit_notes' : parseFloat(credit_notes),
			'iva' : parseFloat($("#iva").val()),
			'credit_note_id' : parseInt($("#credit_note_id").val()),
			'credit_note_amount' : credit_note_amount,
			'scholarship_str' : scholarship_str, 'scholarship_total' : scholarship_total
		};

	for(var i in items_json){
		items_json[i].cost = parseFloat(items_json[i].cost);
		items_json[i].quantity = parseInt(items_json[i].quantity);
		if (items_json[i].month != 0) items_json[i].month = parseInt(items_json[i].month);
		items_json[i].student_id = parseInt(items_json[i].student_id);

	}

	var formUrl = $("#student_bill_form").attr('action');
	var formType = $("#student_bill_form").attr('method');
	var formData = {'item' : item, 'items' : items_json};
	$.ajax({
    	type: formType,
   	 	url: formUrl,
    	data: formData,
    	success: function(data,textStatus,xhr){
    		$("#modalWait").modal('hide');
    		var link = $("#bill_student").attr("href");
			var new_link = link+"/"+data+".pdf";
			$("#bill_student").attr("href",new_link);
			$("#bill_student").text("Descargar PDF");
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

function getLastPaidItem(product){

	var formUrl = basePath + 'bills/getLastMonthPaidProduct';
	var formType = 'POST';
	var formData = {'product' : product, 'student_id' : $('#student').val()};
	$.ajax({
    	type: formType,
   	 	url: formUrl,
    	data: formData,
    	success: function(data,textStatus,xhr){
    		month = parseInt(data);
    		if (Number(month) == 12) month = 0;
    		month_paid = parseInt(month) + 1;

			var month_1 = (month + 1)%12;
			var month_2 = (month + 2)%12
			var month_3 = (month + 3)%12;
			var month_4 = (month + 4)%12;

			if(month_1 == 0) month_1 = 12;
			if(month_2 == 0) month_2 = 12;
			if(month_3 == 0) month_3 = 12;
			if(month_4 == 0) month_4 = 12;
		    
		    $('#month option').each(function(index){
		        if(index == month_1 || index == month_2 || index == month_3 || index == month_4) $(this).prop('disabled',false);
		        else $(this).prop('disabled',true);
		    });

		    $('#month').selectpicker('render');
    	},
    	error: function(xhr,textStatus,error){
    		$("#message").html("");
			$("#message").text("Lo sentimos ha ocurrido un error.");
			$("#modalItemFail").modal('show');
    	}
	});
    
	return false;
}

function cancel() {
	$('#mode_bill').selectpicker('deselectAll');
	$('#mode_bill').selectpicker('render');
	$('#mode_bill_form').data('bootstrapValidator').resetField($('#mode_bill'));
}

function viewModalModeBill(credit){
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
    $('#modalPaymentConfirmation').attr('credit',credit);
	$('#modalModeBill').modal('show');
}

function exonerarItem(item) {

	obj = document.getElementById('exoneration_'+item);
	if (obj.checked == true) {
		//items_json[item].exoneration = parseFloat(items_json[item].cost);
		items_json[item].status = 'paid';
		iva = $('#iva').val();
		iva = parseFloat(iva);
		iva = iva.toFixed(2);
		cost_item = parseFloat(items_json[item].cost) / (1 + (iva / 100));
		cost_item = parseFloat(cost_item);
		cost_item = cost_item.toFixed(2);
		items_json[item].exoneration = cost_item;
		//sub_total_item_iva = parseFloat(items_json[item].sub_total_item) * (iva / 100);
		//total = parseFloat(items_json[item].sub_total_item) + parseFloat(sub_total_item_iva);
		//total = total.toFixed(2);
		items_json[item].paid = items_json[item].cost;
		items_json[item].total = items_json[item].cost;
		items_json[item].checked = true;
	} else { 
		items_json[item].exoneration = 0;
		items_json[item].status = 'pending';
		items_json[item].paid = 0;
		items_json[item].total = 0;
		items_json[item].checked = false;
	}

    generateDetailBill(items_json);
}

function processCredit(credit) {
	credit = parseFloat(credit);
	credit = credit.toFixed(2);
	$('#modalItemPaid').modal('show');
	detail_item = '';
	$('#item-paid').html('');
	for(var i in items_json){
		var cost = 0;
		var pending = 0;
		if(items_json[i].sub_prefix != 'Trans'){
			iva = $('#iva').val();
			iva = parseFloat(iva);
			iva = iva.toFixed(2);
			cost_item_iva = 0;
			sub_total = parseFloat(items_json[i].sub_total_item);
			if (items_json[i].sub_prefix == 'Mat' && parseFloat(items_json[i].exoneration) != 0)
				sub_total = parseFloat(sub_total) - parseFloat(items_json[i].exoneration);
			cost_item_iva = parseFloat(sub_total) * (iva / 100);
			cost = parseFloat(sub_total) + parseFloat(cost_item_iva);
			cost = cost.toFixed(2);
			items_json[i].paid = 0;
			items_json[i].total = cost;
			pending = cost;
		} else {
			cost = items_json[i].sub_total_item;
			pending = cost;
			items_json[i].paid = 0;
			items_json[i].total = cost;
		}

		if (pending != 0) {
			detail_item += '<tr>'+'<td>'+items_json[i].student+'</td>'+
							'<td>'+items_json[i].product+'</td>'+
							'<td>'+items_json[i].month_selected+'</td>'+
							'<td>'+cost+'</td>'+
							'<td id="item_subscriber_'+i+'">'+0+'</td>'+
							'<td id="item_pending_'+i+'">'+pending+'</td>'+
							'<td><input type="checkbox" id="item_'+i+'" onclick="paidItem('+i+')"></td>'+
						'</tr>';
		}
	}

	subscriber_amount = credit;
	subscriber_amount = parseFloat(subscriber_amount);
	subscriber_amount = subscriber_amount.toFixed(2);
	$('#subscriber_amount').val(subscriber_amount);
	$('#item-paid').append(detail_item);
}
var student = "";
var responsable = "";
var item = "";
var itemDelete = "";
var dataStudent = "";
var dataResponsable = "";
var item_title_bill = "";
var item_pay_bill = "";
var item_bill = "";
var observation = "";
var total_payment = "";
var subscriber_amount = 0;
var payments = [];
var credit_notes = 0;
var scholarship_bill = 0;
var scholarship_str = "";
var scholarship_total = 0;

$(document).ready(function() {
	student = localStorage.getItem("Student");
	student = JSON.parse(student);
	responsable = localStorage.getItem("Responsable");
	responsable = JSON.parse(responsable);
	item = localStorage.getItem("Items");
	item = JSON.parse(item);
	generateView(student,responsable);
	generateDetailBill(item);

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
            generateDetailBill(item);
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
                generateDetailBill(item);
            },
            error: function(xhr,textStatus,error){
                console.log(xhr);
            }
        });
    });

	$("#btnCredit").click(function() {
		var validatorObj = $('#bill_form').data('bootstrapValidator');
    	validatorObj.validate();
    	if(validatorObj.isValid()) viewModalModeBill(true);
	});
	
	$('#btnCancelModeBill').click(function() {
		cancel();
	});

	$('#cancelPayment').click(function() {
		cancel();
	});

	$('#btnConfirmation').click(function() {
		$('#btnSubmit').prop('disabled',true);
		$("#btnCredit").prop('disabled',true);
		generateBill();
		$("#modalWait").modal('show');
	});

	$('#btnConfirmationCredit').click(function() {
		var item_paid_subscriber = false;
        for(var i in item){
            status = item[i].Product.status;
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

	$("#btnSubmit").click(function(){
		for(var i in item){
			item[i].Product.status = 'paid';
		}
		if (total_payment == 0) $('#modalBillConfirmation').modal('show');
        else viewModalModeBill(false);
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
                                '<div class="col-md-4">'+
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

	$("#deleteItem").click(function() {
		item_selected = $("#modalItemDelete").attr("item");
		//x is the item delete
		product = item[item_selected].Product.name;
		if (product == 'Transporte') {
			student.date_transport = "";
			student.routes_transport_id = "";
		} else if(product == 'Fitness') student.fitness = 0;
		else if(product == 'Extra Training') student.extra_training = 0;
		item.splice(item_selected,1);
		items = JSON.stringify(item);
		items_json = JSON.parse(items);
		generateDetailBill(items_json);
	});

});

function generateBill() {
	credit = "";
	if ($("#subscriber_amount").val() != "") credit = parseFloat($("#subscriber_amount").val());

	modes_bill = JSON.stringify(payments);
    modes_bill = JSON.parse(modes_bill);

	var bill_data = {'bill_code' : $("#bill_code").val(), 'date_payment' : $("#date_payment").val(),
					 'modes_bill' : modes_bill, 'observation' : $("#observation").val(),
					 'total_payment' : parseFloat(total_payment), 'credit' : credit, 
					 'credit_notes' : parseFloat(credit_notes), 'scholarship_str' : scholarship_str, 'scholarship_total' : scholarship_total};

	var formUrl = $("#bill_form").attr('action');
	var formType = $("#bill_form").attr('method');
	var formData = {'student' : student, 'responsable' : responsable, 'bill_data' : bill_data, 'items' : item};
	
	$.ajax({
    	type: formType,
   	 	url: formUrl,
    	data: formData,
    	success: function(data,textStatus,xhr){
    		//localStorage.clear();
    		response = JSON.parse(data);
    		$("#modalWait").modal('hide');
			var link = 'Se ha registrado el estudiante, el responsable y la factura.'+
					   'Si desea descargar la factura presione el siguiente enlace '+
					   '<a href="printBillStudent/'+response.bill_id+'.pdf" target="_blank">Descargar Factura</a>';
			$("#messageInscription").html(link);
			var link = $("#btnViewStudent").attr("href");
			var new_link = link+"/"+response.student_id;
			$("#btnViewStudent").attr("href",new_link);
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

function generateView(student,responsable,item) {
	var name = student['name'] +' '+ student.lastname;
	dataStudent = '<li>Nombre Completo: ' + name + '</li>';
	if(student.gender_id == 1) dataStudent += '<li>Género: Femenino</li>';
	else dataStudent += '<li>Género: Masculino</li>';
		
	dataStudent += '<li>Fecha de nacimiento: ' + student.birthday + '</li>';
	if (student.phone != "") dataStudent += '<li>Teléfono 1: ' + student.phone + '</li>';
	if (student.home_phone != "") dataStudent += '<li>Teléfono 2: ' + student.home_phone + '</li>';
	if (student.email != "") dataStudent += '<li>Email: ' + student.email + '</li>';
	if (student.alternative_email != "") dataStudent += '<li>Email alternativo: ' + student.alternative_email + '</li>';
	if (student.address != "") dataStudent += '<li>Dirección: ' + student.address + '</li>';
	if (student.responsable != "") dataStudent += '<li>Responsable: ' + student.responsable + '</li>';
	if (student.relation != "") dataStudent += '<li>Relación: ' + student.relation + '</li>';
	if (student.date_inscription != "") dataStudent += '<li>Fecha matricula: ' + student.date_inscription + '</li>';
	$("#dataStudent").html("");
	$("#dataStudent").append(dataStudent);

	dataResponsable = '<li>Nombre Completo: ' + responsable.name + '</li>';
	dataResponsable += '<li>Tipo de documento: ' + responsable.document_type + '</li>';
	dataResponsable += '<li>Número de documento: ' + responsable.document_number + '</li>';
	dataResponsable += '<li>Dirección: ' + responsable.address + '</li>';
	dataResponsable += '<li>Teléfono: ' + responsable.phone + '</li>';
	$("#dataResponsable").html("");
	$("#dataResponsable").append(dataResponsable);
}

function generateDetailBill(items) {
    var detail = "";
    var sub_total = 0;
    var total_iva = 0;
    var total_iva_zero = 0;
    var total = 0;
    var sub_total_item_iva = 0;
    var iva = parseFloat($("#iva").val());
    	iva = iva.toFixed(2);
    var exoneration = 0;
    var scholarship = 0;
    var observation = "";

    $("#item-bill").html("");
    $("#item-pay-bill").html("");
    $("#item-title-bill").html("");

    if (items.length > 0) {
        for(var i in items) {
            cost_item = parseFloat(items[i].Product.cost);
            if(items[i].Product.name != 'Transporte'){
                cost_item = parseFloat(items[i].Product.cost) / (1 + (iva / 100));
                cost_item = parseFloat(cost_item);
                cost_item = cost_item.toFixed(2);
            }

        	if (items[i].Product.name == 'Matricula') {
        		detail += '<tr>'+
                            '<td>'+ items[i].Product.name + '</td>'+
                            '<td class="text-center">'+ items[i].Product.month_str + '</td>'+
                            '<td class="text-center">'+ cost_item + '</td>'+
                          '</tr>';
        	} else {
        		detail += '<tr>'+
                            '<td>'+ items[i].Product.name + '</td>'+
                            '<td class="text-center">'+ items[i].Product.month_str + '</td>'+
                            '<td class="text-center">'+ cost_item + '</td>'+
                            '<td class="text-center"><a href="#" onclick="deleteItem('+i+')"><i class="glyphicon glyphicon-remove remove"></i></a></td>'+
                      '</tr>';
        	}

            if(items[i].Product.name != 'Transporte'){
                //cost = items[i].Product.cost;
                exoneration_iva = 0;
                exoneration_total = 0;
                if (items[i].Product.name == 'Matricula'){
                    if (items[i].Product.exoneration == 1) {
                    	observation += "Se ha exonerado costo de la matricula. ";
	                    //exoneration = items[i].Product.cost;
	                    //cost = 0;
                        exoneration = cost_item;
                        exoneration_iva = parseFloat(items[i].Product.cost) / (1 + (iva / 100));
                        exoneration_iva = parseFloat(items[i].Product.cost) - exoneration_iva;
                        exoneration_iva = parseFloat(exoneration_iva);
                        exoneration_iva = exoneration_iva.toFixed(2);
                        exoneration_total = parseFloat(items[i].Product.cost);
                    }
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
                //sub_total = sub_total.toFixed(2);
                sub_total += parseFloat(cost_item);
                if (cost_item != 0){
                    total_iva += parseFloat(items[i].Product.cost) - parseFloat(cost_item) - parseFloat(scholarship_iva) - parseFloat(exoneration_iva);
                    total += parseFloat(items[i].Product.cost) - parseFloat(items[i].Product.scholarship) - parseFloat(exoneration_total);
                }
            } else {
                total_iva_zero = parseFloat(total_iva_zero) + parseFloat(items[i].Product.cost);
                total_iva_zero = total_iva_zero.toFixed(2);
            }
            //total += parseFloat(items[i].Product.cost);
            observation += items[i].Product.message;
        }

        //scholarship_total = parseFloat(sub_total) * (parseFloat(scholarship_bill) / 100);
        scholarship_total = parseFloat(total) * (parseFloat(scholarship_bill) / 100);
        scholarship_total_iva = parseFloat(total) * (parseFloat(scholarship_bill) / 100);
        scholarship_total = parseFloat(scholarship_total) / (1 + (iva / 100));
        scholarship_total = scholarship_total.toFixed(2);
        scholarship_iva = parseFloat(scholarship_total) * (iva / 100);
        scholarship_iva = scholarship_iva.toFixed(2);
        //total_iva = (parseFloat(sub_total) - parseFloat(exoneration) - parseFloat(scholarship) - parseFloat(scholarship_total)) * (iva / 100);
        //total_iva = Math.abs(total_iva.toFixed(2));
        //total = parseFloat(sub_total) + parseFloat(total_iva) + parseFloat(total_iva_zero) - parseFloat(exoneration) - parseFloat(scholarship) - parseFloat(scholarship_total);
        sub_total = sub_total.toFixed(2);
        total_iva = parseFloat(total_iva) - parseFloat(scholarship_iva);
        total_iva = total_iva.toFixed(2);
        total = parseFloat(total) + parseFloat(total_iva_zero) - parseFloat(scholarship_total_iva);
        total = Math.abs(total.toFixed(2));
        total_payment = Math.abs(total);

        $("#item-bill").append(detail);
        str_iva = $('#iva').val();
        item_title_bill = '<li>Sub Total '+str_iva+'%:</li>';
        if (scholarship_bill != 0 && total != 0) item_title_bill += '<li>Descuento ('+scholarship_str+'):</li>';
        if(exoneration != 0) item_title_bill += '<li>Exoneración:</li>';
        if(scholarship != 0) item_title_bill += '<li>Descuento:</li>';
        item_title_bill += '<li>Sub Total 0%:</li>';
        item_title_bill += '<li>IVA '+str_iva+'%:</li>';
        item_title_bill += '<li>Total:</li>';
        $("#item-title-bill").html("");
        $("#item-title-bill").append(item_title_bill);

        item_pay_bill = '<li>'+ sub_total +'</li>';
        if (scholarship_bill != 0 && total != 0) item_pay_bill += '<li>'+scholarship_total+'</li>';

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

    if (total_payment == 0) $('#btnCredit').prop('disabled',true);
	else $('#btnCredit').prop('disabled',false);
}

function paidItem(i) {
	var cost = 0;
	var pending = 0;
	iva = parseFloat(item[i].Product.iva);
	iva = iva.toFixed(2);
	cost_item_iva = 0;
	cost = item[i].Product.cost
	if (item[i].Product.name == 'Matricula' && item[i].Product.exoneration == 1) {
		cost = 0;
		item[i].Product.status = 'paid';
	}
	cost_item = parseFloat(cost) * (parseFloat(item[i].Product.scholarship) / 100);
	cost_item = parseFloat(cost) - parseFloat(cost_item);
	cost_item_iva = parseFloat(cost_item) * (iva / 100);
	cost = parseFloat(cost_item) + parseFloat(cost_item_iva);
	cost = cost.toFixed(2);
	item[i].Product.paid = 0;
	item[i].Product.total = cost;
	pending = cost;

	if($('#item_'+i).is(':checked') ){
		
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
				item[i].Product.status = 'paid';
				item[i].Product.paid = cost;
			} else {
				subscriber = parseFloat(cost) + parseFloat(pending);
				subscriber = subscriber.toFixed(2);
				item[i].Product.status = 'subscriber';
				item[i].Product.paid = subscriber;
			}
			$('#item_subscriber_'+i).html('');
			$('#item_subscriber_'+i).text(subscriber);

			$('#item_pending_'+i).html('');
			$('#item_pending_'+i).text(pending);
		} else {
			$('#item_'+i).prop('checked',false);
			$("#message").html("");
			$("#message").text("Lo sentimos ya no puede seguir abanando a más items.");
			$("#modalItemFail").modal('show');
		} 

    } else {
    	subscriber = $('#item_subscriber_'+i).text();
    	subscriber_amount = parseFloat(subscriber) + parseFloat(subscriber_amount);
		subscriber_amount = parseFloat(subscriber_amount);
		subscriber_amount = subscriber_amount.toFixed(2);
		$('#item_pending_'+i).html('');
		$('#item_pending_'+i).text(cost);
		$('#item_subscriber_'+i).html('');
		$('#item_subscriber_'+i).text(0);
		item[i].Product.paid = 0;
		item[i].Product.status = 'pending';
		for(var j in item){

			if (item[j].Product.status == 'subscriber') {
				cost_item = parseFloat(item[i].Product.cost) * (parseFloat(item[i].Product.scholarship) / 100);
				cost_item = parseFloat(item[i].Product.cost) - parseFloat(cost_item);
				cost_item_iva = parseFloat(cost_item) * (iva / 100);
				cost = parseFloat(cost_item) + parseFloat(cost_item_iva);
				cost = cost.toFixed(2);

				subscriber = $('#item_subscriber_'+j).text();
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
					item[i].Product.status = 'paid';
					item[i].Product.paid = cost;
				} else {
					subscriber = parseFloat(cost) + parseFloat(pending);
					subscriber = subscriber.toFixed(2);
					item[i].Product.status = 'subscriber';
					item[i].Product.paid = subscriber;
				}
				$('#item_subscriber_'+j).html('');
				$('#item_subscriber_'+j).text(subscriber);
				$('#item_pending_'+j).html('');
				$('#item_pending_'+j).text(pending);
			}
		}
    }
}

function deleteItem(item) {
	$("#modalItemDelete").attr("item",item);
	$("#modalItemDelete").modal('show');
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
                            '<td>'+items_json[i].month_str+'</td>'+
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
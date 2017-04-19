$(document).ready(function() {
	//Calendar in Spanish
	if ($('.input-group.date').length){
		$('.input-group.date').datepicker({
	        language: "es",
	        autoclose: true
	    });
	}

	if ($('#form_report_bill').length) {

		$('#bill_code_from').autocomplete({
			minLength: 2,
			select: function(event,ui) {
				$('#bill_code_from').val(ui.item.label);
			},
			source: function(request,response) {
				$.ajax({
					url: basePath + 'bills/searchBill',
					data: {
						term: request.term
					},
					dataType: 'json',
					success: function(data) {
						response($.map(data, function(el, index) {
							return {
								value: el.Bill.bill_code,
								bill_code: el.Bill.bill_code
							}
						}));
					}
				});
			}
		}).data("ui-autocomplete")._renderItem = function(ul,item){
	        return $("<li></li>")
	        .data("item.autocomplete", item)
	        .append("<a>" + item.bill_code + "</a>")
	        .appendTo(ul)
	    };

	    $('#bill_code_until').autocomplete({
			minLength: 2,
			select: function(event,ui) {
				$('#bill_code_until').val(ui.item.label);
			},
			source: function(request,response) {
				$.ajax({
					url: basePath + 'bills/searchBill',
					data: {
						term: request.term
					},
					dataType: 'json',
					success: function(data) {
						response($.map(data, function(el, index) {
							return {
								value: el.Bill.bill_code,
								bill_code: el.Bill.bill_code
							}
						}));
					}
				});
			}
		}).data("ui-autocomplete")._renderItem = function(ul,item){
	        return $("<li></li>")
	        .data("item.autocomplete", item)
	        .append("<a>" + item.bill_code + "</a>")
	        .appendTo(ul)
	    };

		$('#btnSubmit').click(function() {
			var bill_code_from = $('#bill_code_from').val();
			var bill_code_until = $('#bill_code_until').val();
			var date_from = $('#date_from').val();
			var date_until = $('#date_until').val();
			if(bill_code_from == '' && bill_code_until == '' && date_from == '' && date_until == '') {
				$('#message').html('');
				$('#message').text('Debe colocar los datos para generar el reporte');
				$('#modalMessage').modal('show');
			}else if ((bill_code_from == '' || bill_code_until == '') && (date_from == '' && date_until == '')) {
				$('#message').html('');
				$('#message').text('Debe colocar los códigos de las facturas');
				$('#modalMessage').modal('show');
			} else if((date_from == '' || date_until == '') && (bill_code_from == '' && bill_code_until == '')) { 
				$('#message').html('');
				$('#message').text('Debe colocar las fechas');
				$('#modalMessage').modal('show');
			} else {
				$('#form_report_bill').submit();
			}
		});
	}

	if ($('#form_report_category').length) {
		$('#btnSubmit').click(function() {
			var category = $('#category').selectpicker('val');
			if (category == null) $('#modalMessage').modal('show');
			else $('#form_report_category').submit();
		});

		$('#btnReset').click(function() {
			$('#category').val('').selectpicker('refresh');
		});		
	}

	if ($('#form_report_provider').length) {
		$('#btnSubmit').click(function() {
			var date_from = $('#date_from').val();
			var date_until = $('#date_until').val();
			var provider = $('#provider').selectpicker('val');
			if (provider == null && date_from == '' && date_until == '') {
				$('#message').html('');
				$('#message').text('Debe colocar los datos para generar el reporte');
				$('#modalMessage').modal('show');
			} else if((date_from == '' && date_until == '') && (provider == null)) {
				$('#message').html('');
				$('#message').text('Debe seleccionar al proveedor');
				$('#modalMessage').modal('show');
			} else if((date_from == '' || date_until == '') && (provider == null)) {
				$('#message').html('');
				$('#message').text('Debe colocar las fechas');
				$('#modalMessage').modal('show');
			} else $('#form_report_provider').submit();
		});

		$('#btnReset').click(function() {
			$('#provider').val('').selectpicker('refresh');
			$('#date_from').val('');
			$('#date_until').val('');
		});
	}

	if ($('#form_report_accounts_receivable').length) {
		$('#btnSubmit').click(function() {
			var date_from = $('#date_from').val();
			var date_until = $('#date_until').val();

			if (date_from == '' || date_until == '') $('#modalMessage').modal('show');
			else $('#form_report_accounts_receivable').submit();
		});
	}

	if ($('#form_report_carrier_income').length) {
		$('#btnSubmit').click(function() {
			var date_from = $('#date_from').val();
			var date_until = $('#date_until').val();
			var transport = $('#transport').selectpicker('val');
			if (transport.trim() == '' && date_from == '' && date_until == '') {
				$('#message').html('');
				$('#message').text('Debe colocar los datos para generar el reporte');
				$('#modalMessage').modal('show');
			} else if ((date_from == '' || date_until == '') && transport.trim() == ''){ 
				$('#message').html('');
				$('#message').text('Debe colocar las fechas');
				$('#modalMessage').modal('show');
			} else $('#form_report_carrier_income').submit();
		});

		$('#btnReset').click(function() {
			$('#transport').val('').selectpicker('refresh');
			$('#date_from').val('');
			$('#date_until').val('');
		});
	}
});
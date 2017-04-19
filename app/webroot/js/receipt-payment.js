var filters = [];
var options = "";
$(document).ready(function(){
    if ($('#receipt_form').length) {
        $('#receipt_form')
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
                                    /* Get the selected options */
                                    options = validator.getFieldElements('mode_bill').val();
                                    return (options != null && options.length >= 1 && options.length <= 2);
                                }
                            }
                        }
                    }
                }
            });
        
        $('#payment').click(function() {
            var validatorObj = $('#receipt_form').data('bootstrapValidator');
            validatorObj.validate();
            if(validatorObj.isValid()){
                $('#modes_paid').html('');
                var modes_paid = '';
                var modes_bill = ["Cheque", "Efectivo", "Deposito","Tarjeta de Credito","Transferencia"];
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

        $('#btnConfirmation').click(function() {
            var validatorObj = $('#payment_form').data('bootstrapValidator');
            validatorObj.validate();
            if(validatorObj.isValid()){
                var amount = 0;
                var payments = [];
                var total_pending = parseFloat($('#total_pending').val());
                for (var i = 0; i < options.length; i++) {
                    id = options[i];
                    if ($('#'+id).val() == "") {
                        $('#message').html("");
                        $('#message').text("Debe colocar el monto a abonar en las formas de pago seleccionadas");
                        $('#modalMessage').modal('show');
                    } else {
                        amount = parseFloat(amount) + parseFloat($('#'+id).val());
                        var payment = {'mode_bill' : parseInt(id), 'amount' : parseFloat($('#'+id).val()), 'observation' : $('#observation_'+id).val()};
                        payments.push(payment);
                    }
                }

                if (amount == 0) {
                    $('#message').html("");
                    $('#message').text("El monto a abonar no puede ser 0");
                    $('#modalMessage').modal('show');
                } else if (amount > total_pending) {
                    $('#message').html("");
                    $('#message').text("El monto a abonar debe ser menor o igual monto total de la factura");
                    $('#modalMessage').modal('show');
                } else {
                    items = JSON.stringify(payments);
                    items_json = JSON.parse(items);
                    $('#modalPaymentConfirmation').modal('hide');
                    $('#modalWait').modal('show');
                    bill_id = $('#bill_id').val();
                    receipt_code = $('#receipt_code').val();
                    var formUrl = $('#receipt_form').attr('action');
                    var formType = $('#receipt_form').attr('method');
                    var formData = {'items' : items_json, 'bill_id' : bill_id, 'receipt_code' : receipt_code};
                    
                    $.ajax({
                        type: formType,
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            $("#modalWait").modal('hide');
                            var link = $('#receipt_payment').attr('href');
                            var new_link = link+"/"+data+".pdf";
                            $("#receipt_payment").attr("href",new_link);
                            $("#modalPaymentSuccess").modal('show');
                        },
                        error: function(xhr,textStatus,error){
                            $("#modalWait").modal('hide');
                            $("#message").text("Ha ocurrido un error al tratar de realizar el abono!");
                            $("#modalMessage").modal('show');
                        }
                    }); 
                    
                    return false;      
                }
            }
        });
    }

    //Calendar in Spanish
    if ($('.input-group.date').length) {
        $('.input-group.date').datepicker({
            language: "es",
            autoclose: true
        });
    }

    $('#reset').click(function(){
        $('#search').val('');  //Limpiamos barra busqueda
        $('#date_from').val('');
        $('#date_until').val('');
        $('.order-table >tbody >tr').show(); //Mostramos todas las entradas
        LightTableFilter.init();  //iniciamos el plugin
    });

    $('#filter_date_range').click(function(){
        apply_filter_date_range('.order-table tbody', 4, $('#date_from').val(), $('#date_until').val());
    });

    var LightTableFilter = (function(Arr) {
        var _input;

        function _onInputEvent(e) {
            _input = e.target;
            var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
            Arr.forEach.call(tables, function(table) {
                Arr.forEach.call(table.tBodies, function(tbody) {
                        Arr.forEach.call(tbody.rows, _filter);
                });
            });
        }

        function _filter(row) {
            var text = row.textContent.toLowerCase(),
            val = _input.value.toLowerCase();
            row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        }

        return {
            init: function() {
                var inputs = document.getElementsByClassName('light-table-filter');
                Arr.forEach.call(inputs, function(input) {
                        input.oninput = _onInputEvent;
                });
            }
        };
    })(Array.prototype);

    document.addEventListener('readystatechange', function() {
        if (document.readyState === 'complete')
            LightTableFilter.init();
    });
});

function apply_filter_date_range (table, col, date_from, date_until) {

    var new_date_from, new_date_until;
    if (date_from !== "") {
        df = date_from.split("-");
        new_date_from = new Date(df[0], parseInt(df[1])-1, df[2]);  // -1 porque los meses son de 0 a 11
    }

    if (date_until !== "") {
        dt = date_until.split("-");
        new_date_until = new Date(dt[0], parseInt(dt[1])-1, dt[2]);
    }

    // Establezo el estado (visible u oculto) de cada fila de la tabla
    $(table).find('tr td:nth-child('+col+')').each(function(i){
        // Obtengo la fecha que esta en la fila i de la tabla
        var date_check = $(this).text();
        var dc = date_check.split("-");
        var check = new Date(dc[0], parseInt(dc[1])-1, dc[2]);

        if (date_from !== "" && date_until !== "") {
            if (check >= new_date_from && check <= new_date_until) $(this).parent().data('passed', true);
            else $(this).parent().data('passed', false);

        } else if (date_from !== "" && date_until === "") {
            if(check >= new_date_from) $(this).parent().data('passed', true);
            else $(this).parent().data('passed', false);

        } else if (date_from === "" && date_until !== "") {
            if(check <= new_date_until) $(this).parent().data('passed', true);
            else $(this).parent().data('passed', false);
        } else {
            $(table).find('tr').each(function(i){
                $(this).data('passed', true);
            });
        }

    });
    // Refresco la tabla
    $(table).find('tr').each(function(i){
        if(!$(this).data('passed')) $(this).hide();
        else $(this).show();
    });
}
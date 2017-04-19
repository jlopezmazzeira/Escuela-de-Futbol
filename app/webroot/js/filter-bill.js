var total_cash = 0;
var total_cash_close = 0;
var hundred_dollars = 0;
var fifty_dollars = 0;
var twenty_dollars = 0;
var ten_dollars = 0;
var five_dollars = 0;
var one_dollar = 0;
var fifty_cents = 0;
var twenty_five_cents = 0;
var ten_cents = 0;
var five_cents = 0;
var one_cent = 0;
var receipts = "";
var bills = "";
var dates_closing = "";
var total = 0;
$(document).ready(function() {
    //Calendar in Spanish
    $('.input-group.date').datepicker({
        language: "es",
        autoclose: true
    });

    $('#generateOrder').click(function () {
        processOperation(true);
    });

    $('#cancel').click(function () {
       processOperation(false); 
    });

    $('#generateClosingC').click(function() {
        $("#modalGenerateClosing").modal('hide');
        var formUrl = basePath + 'closings/generateClosing';
        var formType = 'POST';
        var formData = {'generate' : true};
        $.ajax({
            type: formType,
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){
                var response = JSON.parse(data);
                total = response.total.total_check + response.total.total_deposit + response.total.total_transfer + response.total.total_tdc + response.total.total_cash;
                total = parseFloat(total);
                total = total.toFixed(2);
                total_cash_close = response.total.total_cash;
                $('#total_check').text('$'+response.total.total_check);
                $('#total_deposit').text('$'+response.total.total_deposit);
                $('#total_cash').text('$'+response.total.total_cash);
                $('#total_tdc').text('$'+response.total.total_tdc);
                $('#total_transfer').text('$'+response.total.total_transfer);
                $('#total').text('$'+total);
                $('#bills_data').html("");
                bills = JSON.stringify(response.bills);
                bills = JSON.parse(bills);
                if (bills.length > 0) {
                    details_bill = "";
                    number = 0;
                    for(var i in bills) {
                        for(var j in bills[i]) {
                            number += 1;
                            details_bill += '<tr>'+
                                                '<td>'+ number + '</td>'+
                                                '<td class="text-center">'+ bills[i][j].b.bill_code + '</td>'+
                                                '<td class="text-center">'+ bills[i][j].mb.name + '</td>'+
                                                '<td>'+ bills[i][j].InvoicesPayment.observation + '</td>'+
                                                '<td class="text-center">'+ bills[i][j].b.date_payment + '</td>'+
                                                '<td class="text-center">'+ bills[i][j].InvoicesPayment.subscribed_amount + '</td>'+
                                            '</tr>';
                        }
                    }
                    $('#bills_data').append(details_bill);
                    $('#table_bills').removeAttr('hidden');
                }
                $('#receipts_data').html("");
                receipts = JSON.stringify(response.receipts);
                receipts = JSON.parse(receipts);

                if (receipts.length > 0){
                    details_receipt = "";
                    number = 0;
                    for(var i in receipts) {
                        for(var j in receipts[i]) {
                            number += 1;
                            details_receipt += '<tr>'+
                                                    '<td>'+ number + '</td>'+
                                                    '<td class="text-center">'+ receipts[i][j].r.receipt_code + '</td>'+
                                                    '<td class="text-center">'+ receipts[i][j].b.bill_code + '</td>'+
                                                    '<td class="text-center">'+ receipts[i][j].mb.name + '</td>'+
                                                    '<td>'+ receipts[i][j].InvoicesPayment.observation + '</td>'+
                                                    '<td class="text-center">'+ receipts[i][j].r.date_payment + '</td>'+
                                                    '<td class="text-center">'+ receipts[i][j].InvoicesPayment.subscribed_amount + '</td>'+
                                                '</tr>';
                        }
                    }
                    $('#table_receipts').removeAttr('hidden');
                    $('#receipts_data').append(details_receipt);
                }
                dates_closing = JSON.stringify(response.dates_closing);
                dates_closing = JSON.parse(dates_closing);
                $("#modalGenerateClosingConfirmation").modal('show');
            },
            error: function(xhr,textStatus,error){
                $(".message").text("Ha ocurrido un error al tratar de generar el cierre!");
                $("#modalMessage").modal('show');
            }
        }); 
        
        return false;    
    });
    
    $('#resetValue').click(function() {
        $('#hundred_dollars').val(0);
        $('#fifty_dollars').val(0);
        $('#twenty_dollars').val(0);
        $('#one_dollar').val(0);
        $('#ten_dollars').val(0);
        $('#five_dollars').val(0);
        $('#fifty_cents').val(0);
        $('#twenty_five_cents').val(0);
        $('#ten_cents').val(0);
        $('#five_cents').val(0);
        $('#one_cent').val(0);
        $('#total_deposit_cash').val(0);
    });

    $('#hundred_dollars').keyup(function() {
        hundred_dollars = calculate('hundred_dollars',$('#hundred_dollars').val(),100);
        total_cash = calculateTotalCash();
    });

    $('#hundred_dollars').change(function() {
        hundred_dollars = calculate('hundred_dollars',$('#hundred_dollars').val(),100);
        total_cash = calculateTotalCash();
    });

    $('#fifty_dollars').keyup(function() {
        fifty_dollars = calculate('fifty_dollars',$('#fifty_dollars').val(),50);
        total_cash = calculateTotalCash();
    });

    $('#fifty_dollars').change(function() {
        fifty_dollars = calculate('fifty_dollars',$('#fifty_dollars').val(),50);
        total_cash = calculateTotalCash();
    });

    $('#twenty_dollars').keyup(function() {
        twenty_dollars = calculate('twenty_dollars',$('#twenty_dollars').val(),20);
        total_cash = calculateTotalCash();
    });

    $('#twenty_dollars').change(function() {
        twenty_dollars = calculate('twenty_dollars',$('#twenty_dollars').val(),20);
        total_cash = calculateTotalCash();
    });

    $('#ten_dollars').keyup(function() {
        ten_dollars = calculate('ten_dollars',$('#ten_dollars').val(),10);
        total_cash = calculateTotalCash();
    });

    $('#ten_dollars').change(function() {
        ten_dollars = calculate('ten_dollars',$('#ten_dollars').val(),10);
        total_cash = calculateTotalCash();
    });

    $('#five_dollars').keyup(function() {
        five_dollars = calculate('five_dollars',$('#five_dollars').val(),5);
        total_cash = calculateTotalCash();
    });

    $('#five_dollars').change(function() {
        five_dollars = calculate('five_dollars',$('#five_dollars').val(),5);
        total_cash = calculateTotalCash();
    });    

    $('#one_dollar').keyup(function() {
        one_dollar = calculate('one_dollar',$('#one_dollar').val(),1);
        total_cash = calculateTotalCash();
    });

    $('#one_dollar').change(function() {
        one_dollar = calculate('one_dollar',$('#one_dollar').val(),1);
        total_cash = calculateTotalCash();
    });

    $('#fifty_cents').keyup(function() {
        fifty_cents = calculate('fifty_cents',$('#fifty_cents').val(),0.50);
        total_cash = calculateTotalCash();
    });

    $('#fifty_cents').change(function() {
        fifty_cents = calculate('fifty_cents',$('#fifty_cents').val(),0.50);
        total_cash = calculateTotalCash();
    });

    $('#twenty_five_cents').keyup(function() {
        twenty_five_cents = calculate('twenty_five_cents',$('#twenty_five_cents').val(),0.25);
        total_cash = calculateTotalCash();
    });

    $('#twenty_five_cents').change(function() {
        twenty_five_cents = calculate('twenty_five_cents',$('#twenty_five_cents').val(),0.25);
        total_cash = calculateTotalCash();
    });

    $('#ten_cents').keyup(function() {
        ten_cents = calculate('ten_cents',$('#ten_cents').val(),0.1);
        total_cash = calculateTotalCash();
    });

    $('#ten_cents').change(function() {
        ten_cents = calculate('ten_cents',$('#ten_cents').val(),0.1);
        total_cash = calculateTotalCash();
    });

    $('#five_cents').keyup(function() {
        five_cents = calculate('five_cents',$('#five_cents').val(),0.05);
        total_cash = calculateTotalCash();
    });

    $('#five_cents').change(function() {
        five_cents = calculate('five_cents',$('#five_cents').val(),0.05);
        total_cash = calculateTotalCash();
    });

    $('#one_cent').keyup(function() {
        one_cent = calculate('one_cent',$('#one_cent').val(),0.01);
        total_cash = calculateTotalCash();
    });

    $('#one_cent').change(function() {
        one_cent = calculate('one_cent',$('#one_cent').val(),0.01);
        total_cash = calculateTotalCash();
    });

    $('#generateClosingConfirmation').click(function() {
        total_deposit_cash = $('#total_deposit_cash').val();
        total_deposit_cash = parseFloat(total_deposit_cash);
        total_deposit_cash = total_deposit_cash.toFixed(2);
        total_cash_close = parseFloat(total_cash_close);
        total_cash_close = total_cash_close.toFixed(2);

        if (total_deposit_cash == total_cash_close) {
            quantity_hundred_dollars = ($('#hundred_dollars').val() != "") ? parseInt($('#hundred_dollars').val()) : 0;
            quantity_fifty_dollars = ($('#fifty_dollars').val() != "") ? parseInt($('#fifty_dollars').val()) : 0;
            quantity_twenty_dollars = ($('#twenty_dollars').val() != "") ? parseInt($('#twenty_dollars').val()) : 0;
            quantity_ten_dollars = ($('#ten_dollars').val() != "") ? parseInt($('#ten_dollars').val()) : 0;
            quantity_five_dollars = ($('#five_dollars').val() != "") ? parseInt($('#five_dollars').val()) : 0;
            quantity_one_dollar = ($('#one_dollar').val() != "") ? parseInt($('#one_dollar').val()) : 0;
            quantity_fifty_cents = ($('#fifty_cents').val() != "") ? parseInt($('#fifty_cents').val()) : 0;
            quantity_twenty_five_cents = ($('#twenty_five_cents').val() != "") ? parseInt($('#twenty_five_cents').val()) : 0;
            quantity_ten_cents = ($('#ten_cents').val() != "") ? parseInt($('#ten_cents').val()) : 0;
            quantity_five_cents = ($('#five_cents').val() != "") ? parseInt($('#five_cents').val()) : 0;
            quantity_one_cent = ($('#one_cent').val() != "") ? parseInt($('#one_cent').val()) : 0;
            

            $("#modalGenerateClosingConfirmation").modal('hide');
            $("#modalWait").modal('show');
            var formUrl = basePath + 'closings/add';
            var formType = 'POST';
            var coins = {'quantity_hundred_dollars' : quantity_hundred_dollars,
                            'quantity_fifty_dollars' : quantity_fifty_dollars,
                            'quantity_twenty_dollars' : quantity_twenty_dollars,
                            'quantity_ten_dollars' : quantity_ten_dollars,
                            'quantity_five_dollars' : quantity_five_dollars,
                            'quantity_one_dollar' : quantity_one_dollar,
                            'quantity_fifty_cents' : quantity_fifty_cents,
                            'quantity_twenty_five_cents' : quantity_twenty_five_cents,
                            'quantity_ten_cents' : quantity_ten_cents,
                            'quantity_five_cents' : quantity_five_cents,
                            'quantity_one_cent' : quantity_one_cent
                            };

            var formData = {'bills' : bills, 'receipts' : receipts, 'dates_closing' : dates_closing, 
                            'total' : parseFloat(total), 'coins' : coins};
            
            $.ajax({
                type: formType,
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    $("#modalWait").modal('hide');
                    link = $('#closing_id').attr('href');
                    new_link = link+'/'+data+'.pdf';
                    $(".message").text("El cierre se ha generado exitosamente. Para descargar el cierre presione el siguiente enlace ");
                    $('.message').append('<a href="'+new_link+'">Descargar Cierre</a>');
                    $('#btnMessageSuccess').removeAttr('hidden');
                    $('#btnMessage').attr('hidden','true');
                    $("#modalMessage").modal('show');
                },
                error: function(xhr,textStatus,error){
                    $("#modalWait").modal('hide');
                    $(".message").text("Ha ocurrido un error al tratar de generar el cierre!");
                    $("#modalMessage").modal('show');
                }
            }); 
            
            return false;
        } else showPopup();

    });

    $('#filter_date_range').click(function(){
        apply_filter_date_range('.order-table tbody', 5, $('#date_from').val(), $('#date_until').val());
    });

    $('#messageAmount').click(function() {
        showPopup();
    });

	$('#reset').click(function(){
        $('#search').val('');  //Limpiamos barra busqueda
        $('#date_from').val('');
        $('#date_until').val('');
        $('.order-table >tbody >tr').show(); //Mostramos todas las entradas
        LightTableFilter.init();  //iniciamos el plugin
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

function calculate(id,quantity,nomination){
    var input = document.getElementById(id).value.replace(/\D/g, '').replace(/^0+/, '');
    if (isNaN(input)) input = 0;
    document.getElementById(id).value = input;
    var result;
    if (nomination % 1 === 0) result = parseInt((input) * (nomination));
    else result = parseFloat((input) * (nomination));

    return result;
}

function calculateTotalCash(){
    var total = parseInt(hundred_dollars + fifty_dollars + twenty_dollars + ten_dollars + five_dollars + one_dollar) 
                + parseFloat(fifty_cents + twenty_five_cents + ten_cents + five_cents + one_cent);
        total = parseFloat(total);
        total = total.toFixed(2);
    $('#total_deposit_cash').val(total);
    return total;
}

function cancelInvoice(bill){
	$("#modalCancelBill").modal('show');
	$("#modalCancelBill").attr("bill", bill);	
}

function processOperation(generate) {
    var bill = $('#modalCancelBill').attr('bill');
    $("#modalCancelBill").modal('hide');
    var formUrl = $('#cancel_bill_form').attr('action');
    var formType = $('#cancel_bill_form').attr('method');
    var formData = {'bill_id' : bill, 'generate' : generate};
    $.ajax({
        type: formType,
        url: formUrl,
        data: formData,
        success: function(data,textStatus,xhr){
            if (data) $(".message").text("Factura anulada satifactoriamente!");
            else $(".message").text("La factura no se puede anular, pertenece a un cierre!");
            
            $("#modalMessage").modal('show');
        },
        error: function(xhr,textStatus,error){
            $(".message").text("Ha ocurrido un error al tratar de anular la factura!");
            $("#modalMessage").modal('show');
        }
    }); 
    
    return false;
}

function generateClosing(){
    var formUrl = basePath + 'bills/verifyInvoices';
    var formType = 'POST';
    var formData = {'verify' : true};
    $.ajax({
        type: formType,
        url: formUrl,
        data: formData,
        success: function(data,textStatus,xhr){
            if (data == 0) {
                $(".message").text("No hay datos para realizar el cierre!");
                $("#modalMessage").modal('show');
            } else $("#modalGenerateClosing").modal('show');
        },
        error: function(xhr,textStatus,error){
            $(".message").text("Ha ocurrido un error al tratar de verificar las facturas!");
            $("#modalMessage").modal('show');
        }
    }); 
    
    return false;
}

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

function showPopup() {
    var popup = document.getElementById("messageAmount");
    popup.classList.toggle("show");
}
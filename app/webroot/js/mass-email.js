var filters = [];
var filtro = "all";

$(document).ready(function() {

    if ($('#email_form').length) {
        $('#email_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                title_message: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el asunto'
                        }
                    }
                },
                message: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el mensaje'
                        }
                    }
                }
            }
        });
    }

    $('.inactives').hide();

    $('#reset').click(function(){
        $('#search').val('');  //Limpiamos barra busqueda
        $('.filters').prop('checked',false);
        $('.order-table >tbody >tr').show(); //Mostramos todas las entradas
        LightTableFilter.init();  //iniciamos el plugin
    });

	$('.filters').change(function(){
           filters = [];
            filtro = $(this).val(); //Obtengo el status del estudiante
            if(filtro == "Ina") $('.inactives').show(); //Mostramos todo
            $('#type_filter').val(filtro); //Mandamos al Controlador
            apply_filter('.order-table tbody', 7, $(this).val());  //Aplicamos filtro
    });
    
    $('#email_form').submit(function() {
        var title_message = $('#title_message').val();
        var message = $('#message').val();
        if (title_message != "" && message != "") {
            $('#send').prop('disabled',true);
            $('#modalWait').modal('show');
            var student_status = $('#type_filter').val();
            
            var formUrl = $(this).attr('action');
            var formType = $(this).attr('method');
            var formData = {'student_status' : student_status, 'title_message' : title_message, 'message' : message};
            
            $.ajax({
                type: formType,
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    response = JSON.parse(data);
                    $('#modalWait').modal('hide');
                    $('#message_email').html("");
                    $('#message_email').text("Se han enviado " + response.email_send_success + " email(s) satisfactoriamente de " + response.size);
                    $('#modalMessage').modal('show');
                },
                error: function(xhr,textStatus,error){
                    $("#modalWait").modal('hide');
                    $('#message_email').html("");
                    $('#message_email').text("Ha ocurrido un problema al enviar los correos.");
                    $('#modalMessage').modal('show');
                }
            }); 
            
            return false;
        }
        
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

function apply_filter(table,col,text){
    filters[col] = text;

    $(table).find('tr').each(function(i){
        $(this).data('passed', true);
    });

    for(index in filters)
    {
        if(filters[index] !== 'any')
        {
            $(table).find('tr td:nth-child('+index+')').each(function(i){
                if($(this).text().indexOf(filters[index]) > -1 && $(this).parent().data('passed'))
                    $(this).parent().data('passed', true);
                else
                    $(this).parent().data('passed', false);
            });
        }
    }

    $(table).find('tr').each(function(i){
        if(!$(this).data('passed')) $(this).hide();
        else $(this).show();
    });
}
$(document).ready(function() {

    //Calendar in Spanish
    $('.input-group.date').datepicker({
        language: "es",
        autoclose: true
    });

    $('#report').click(function() {
        $('#form_report_student').submit();
    });
    
    $('#filter_date_range').click(function(){
        apply_filter_date_range('.order-table tbody', 7, $('#date_from').val(), $('#date_until').val());
        $('#date_from_report').val($('#date_from').val());
        $('#date_until_report').val($('#date_until').val());
    });

    $('#reset').click(function(){
        $('#search').val('');  //Limpiamos barra busqueda
        $('#date_from').val('');
        $('#date_until').val('');
        $('#date_from_form').val('');
        $('#date_until_form').val('');
        $('.filters').prop('checked',false);
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
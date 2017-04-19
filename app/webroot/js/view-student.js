$(document).ready(function(){
	
	var date = $("#date_inscription_es").text();
    var translate = format_date(date);
    $('#date_inscription_es').html("");
    $('#date_inscription_es').html(translate);

    $('input[type=file]').change(function() {
    	$('#form_upload').submit();
    });

    $('#image').click(function(){
       var url = $(this).attr('file');
       window.open(url);
    });
});

function format_date(date){  //Formato de Fecha
    var tokens = date.split("-");
    var year = tokens[0]; //Obtengo ano
    var day = tokens[2]; //Obtengo dia
    var months = [ "Enero", "Febrero", "Marzo","Abril", "Mayo", "Junio", "Julio","Agosto", "Septiembre", "Octubre",
    "Noviembre", "Diciembre"];
    var translate = day+' de '+months[parseInt(tokens[1]-1)]+' del '+ year; //obtengo el int del mes y resto 1
    return translate;
}
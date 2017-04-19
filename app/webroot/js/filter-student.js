var filters = [];
$(document).ready(function() {
    generateViewStudents(filters);  
    $('#report').click(function() {
        $('#form_report_student').submit();
    });

    $('#order_form').submit(function(){
        //serialize form data
        //var formData = $(this).serialize();
        //get form action
        var student = $('#modalGenerateOrderStudent').attr('student');
        $("#modalGenerateOrderStudent").modal('hide');
        var formUrl = $(this).attr('action');
        var formType = $(this).attr('method');
        var formData = {'student_id' : student};
        $.ajax({
            type: formType,
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){
                $(".message").text("Se ha generado la orden de pago satifactoriamente!");
                $("#modalMessage").modal('show');
            },
            error: function(xhr,textStatus,error){
                $(".message").text("Ha ocurrido un error al tratar de generar la orden de pago del estudiante!");
                $("#modalMessage").modal('show');
            }
        }); 
        
        return false;
    });

    $('#reset').click(function(){
        $('#search').val('');  //Limpiamos barra busqueda
        $('.filters').prop('checked',false);
        filters = [];
        generateViewStudents(filters);
        LightTableFilter.init();  //iniciamos el plugin
    });

	$('.filters').change(function(){
        if($(this).is(':checked')){
            if (filters.length == 0) {
                var status = {'status' : $(this).val()}
                filters.push(status);
            } else {
                status_boolean = false;
                for (var i = 0; i < filters.length; i++) {
                    if(filters[i].status == $(this).val()) status_boolean = true; 
                }

                if (!status_boolean){
                    var status = {'status' : $(this).val()}
                    filters.push(status);
                }
            }
        } else {
            for (var i = 0; i < filters.length; i++) {
                if(filters[i].status == $(this).val()) filters.splice(i, 1);
            }
        }
    });
    
    $('#btnSearch').click(function() {
        generateViewStudents(filters);
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

    if ($('#form_change_status').length) {
        $('#form_change_status').bootstrapValidator({
            framework: 'bootstrap',
            message: 'This value is not valid',
            submitButton: '#btnSubmit',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                observation: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el motivo'
                        }
                    }
                }
            }
        });

        $('#status').change(function(){
            var status = $(this).val();
            var status_previus = $('#status_previus').val();
            if(Number(status) == 1 &&  Number(status_previus) == 3){
                $('#div_date').removeAttr('hidden');
                $('#date_payment').attr('required',true);
                $('#form_change_status').bootstrapValidator('addField', $('#date_payment'), {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha'
                        }
                    }
                });
            } else {
                $('#status')
                    .on('change', function(e) {
                        $('#form_change_status').bootstrapValidator('revalidateField', 'date_payment');
                });
                $('#date_payment').removeAttr('required');
                $('#date_payment').val('');
                $('#div_date').attr('hidden',true);
            }
        });
    
        $('#date_payment_div')
            .on('changeDate show', function(e) {
                $('#form_change_status').bootstrapValidator('revalidateField', 'date_payment');
        });
    }
});

function generateViewStudents(status_filter) {
    status_json = JSON.stringify(status_filter);
    status_json = JSON.parse(status_json);
    var formUrl = basePath + 'students/getStudents';
    var formType = "POST";
    var formData = {'status' : status_json};
    
    $.ajax({
        type: formType,
        url: formUrl,
        data: formData,
        success: function(data,textStatus,xhr){
            items_json = JSON.parse(data);
            array_students = [];
            for (var i = 0; i < items_json.length; i++) {
                for (var j = 0; j < items_json[i].length; j++) {

                    student = {
                        'id' : parseInt(items_json[i][j].Student.id),
                        'name' : items_json[i][j].Student.lastname+' '+items_json[i][j].Student.name,
                        'category' : items_json[i][j].c.name,
                        'age' : items_json[i][j].Student.age,
                        'responsable' : items_json[i][j].Student.responsable,
                        'email' : items_json[i][j].Student.email,
                        'code_final' : items_json[i][j].Student.code_final,
                        'status' : parseInt(items_json[i][j].Student.status),
                        'status_str' : items_json[i][j].Student.status_str,
                        'status_class' : items_json[i][j].Student.status_class,
                        'img' : items_json[i][j].Student.imagen,
                        'class_renew' : items_json[i][j].Student.class_renew 
                    }

                    array_students.push(student);
                }
            }
            sortStudents(array_students);
        },
        error: function(xhr,textStatus,error){
            console.log(xhr);
        }
    });

    return false;
}

function sortStudents(students) {
    if (students.length == 0) {
        $('#data_students').html('<tr><td colspan="8" class="text-center">No existen estudiantes registrados</td></tr>');
    } else {
        $('#data_students').html('');
        data_students = '';
        students.sort(function (a, b) {
            if (a.name > b.name) return 1;
            if (a.name < b.name) return -1;
            return 0;
        });

        for (var i = 0; i < students.length; i++) {
            var a = '<a href="'+basePath+'students/edit/'+students[i].id+'" title="Editar"><i class="fa fa-pencil action" aria-hidden="true"></i></a> ';
            var b = ''; 
            if (students[i].status == 1) 
                b = '<a href="'+basePath+'bills/studentBill/'+students[i].id+'" title="Factura"><i class="fa fa-list-alt action" aria-hidden="true"></i></a> ';
            else if (students[i].status == 2)
                b = '<a href="'+basePath+'orders/pendingPayments/'+students[i].id+'" title="Pago pendiente"><i class="fa fa-list-alt action" aria-hidden="true"></i></i></a> ';
            else if (students[i].status == 5)
                b = '<a href="#" onclick="generateOrderStudent('+students[i].id+')" title="Generar orden"><i class="fa fa-refresh action"></i></a> ';

            var c = '<a href="#" onclick="deleteStudent('+students[i].id+')" title="Eliminar"><i class="fa fa-trash-o action delete"></i></a> ';
            data_students += '<tr>'+
                            '<td>'+students[i].code_final+'</td>'+
                            '<td><a href="'+basePath+'students/view/'+students[i].id+'"><img src="'+basePath+'img/'+students[i].img+'" class="'+students[i].class_renew+'"></a> '+students[i].name+'</td>'+
                            '<td>'+students[i].email+'</td>'+
                            '<td>'+students[i].age+'</td>'+
                            '<td>'+students[i].category+'</td>'+
                            '<td>'+students[i].responsable+'</td>'+
                            '<td class="'+students[i].status_class+'"><strong>'+students[i].status_str+'</strong></td>'+
                            '<td>'+a + b + c +'</td>'+
                         '</tr>';
        }

        $('#data_students').html(data_students);
    }
    
}

function generateOrderStudent(student) {
    $("#modalGenerateOrderStudent").modal('show');
    $("#modalGenerateOrderStudent").attr("student", student);
}
var response = "";
$(document).ready(function() {
    if ($('.input-group.date').length) {
        //Calendar in Spanish
        $('.input-group.date').datepicker({
            language: "es",
            autoclose: true
        });
    }
    
    if ($('#control_consult_form').length) {
        var response = "";
        $('#control_consult_form').bootstrapValidator({
            framework: 'bootstrap',
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                date_control: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la categoría'
                        }
                    }
                }
            }
        });

        $('#date_control_div')
            .on('changeDate show', function(e) {
                $('#control_consult_form').bootstrapValidator('revalidateField', 'date_control');
        });

        $('#btnReset').click(function() {
            $('#date_control').val('');
            $('#category').val('').selectpicker('refresh');
            $('#control_consult_form').data('bootstrapValidator').resetField($('#date_control'));
            $('#control_consult_form').data('bootstrapValidator').resetField($('#category'));
        });

        $('#btnConsult').click(function() {
            var validatorObj = $('#control_consult_form').data('bootstrapValidator');
                validatorObj.validate();
            if(validatorObj.isValid()){
                category_id = $('#category').val();
                if (category_id != '') {
                    var formUrl = basePath + 'controls/getControl';
                    var formType = "POST";
                    var formData = {'category_id' : parseInt(category_id), 'date_control' : $('#date_control').val()};
                    $.ajax({
                        type: formType,
                        url: formUrl,
                        data: formData,
                        success: function(data,textStatus,xhr){
                            response = JSON.parse(data);
                            students = '';
                            $('#message').html("");
                            $('#students').html('');
                            $('#div_students').attr('hidden',true);
                            $('#div_btn').attr('hidden',true);
                            if (response.length > 0) {
                                $('#div_students').removeAttr('hidden');
                                $('#div_btn').removeAttr('hidden');
                                for (var i = 0; i < response.length; i++) {
                                    
                                    students += '<tr>'+
                                                    '<td>'+response[i].s.code+'</td>'+
                                                    '<td>'+response[i].s.name +' '+response[i].s.lastname+'</td>'+
                                                    '<td>'+response[i].s.age+'</td>'+
                                                    '<td>'+response[i].s.status_str+'</td>'+
                                                    '<td><input type="checkbox" id="assistance_'+response[i].s.id+'" name="assistance_'+response[i].s.id+'" class="checkbox" checked/></td>'+
                                                    '<td><textarea id="observation_'+response[i].s.id+'" name="observation_'+response[i].s.id+'" class="form-control" rows="1">'+
                                                    response[i].DetailsControl.observation+'</textarea></td>'+
                                                '</tr>';
                                }
                                $('#students').append(students);
                                for (var i = 0; i < response.length; i++) {
                                    if (Number(response[i].DetailsControl.assistance) == 0)
                                        $('#assistance_'+response[i].s.id).attr('checked',false); 
                                }
                            } else {
                                $('#message').text("En este día no se ha guardado la asistencia.");
                                $('#modalMessage').modal('show');
                            }
                        },
                        error: function(xhr,textStatus,error){
                            $('#message').html("");
                            $('#message').text("Ha ocurrido un error al tratar de buscar los estudiantes de dicha categoría.");
                            $('#modalMessage').modal('show');
                        }
                    });
                }
            }
        });

        $('#btnSubmit').click(function() {
            $('#modalConfirmation').modal('show');
        });

        $('#btnConfirmation').click(function() {
            $('#modalConfirmation').modal('hide');
            $('#modalWait').modal('show');
            for (var i = 0; i < response.length; i++) {
                var assistance = 1;
                if(!$('#assistance_'+response[i].s.id).is(':checked')) assistance = 0;
                response[i].DetailsControl.assistance = assistance;
                response[i].DetailsControl.observation = $('#observation_'+response[i].s.id).val();
            }

            var formUrl = basePath + 'controls/update';
            var formType = "POST";
            var formData = {'category_id' : parseInt($('#category').val()), 'students' : response, 'date_control' : $('#date_control').val()};
            
            $.ajax({
                type: formType,
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    $('#modalWait').modal('hide');
                    $('#modalSuccess').modal('show');
                },
                error: function(xhr,textStatus,error){
                    $('#modalWait').modal('hide');
                    $('#message').html("");
                    $('#message').text("Ha ocurrido un error al tratar de guardar la asistencia.");
                    $('#modalMessage').modal('show');
                }
            });

            return false;
        });
    }

    if ($('#form_check_category').length) {
        $('#form_check_category').bootstrapValidator({
            framework: 'bootstrap',
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                month: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el mes'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la categoría'
                        }
                    }
                }
            }
        });

        $('#btnReset').click(function() {
            $('#category').val('').selectpicker('refresh');
            $('#month').val('').selectpicker('refresh');
            $('#form_check_category').data('bootstrapValidator').resetField($('#month'));
            $('#form_check_category').data('bootstrapValidator').resetField($('#category'));
        });
    }

    if ($('#control_form').length) {
        $('#control_form').bootstrapValidator({
            framework: 'bootstrap',
            message: 'This value is not valid',
            submitButton: '#btnSubmit',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                date_control: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha de la asistencia'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la categoría'
                        }
                    }
                }
            }
        });

        $('#date_control_div')
            .on('changeDate show', function(e) {
                $('#control_form').bootstrapValidator('revalidateField', 'date_control');
        });

        $('#category').change(function() {
            category_id = $(this).val();
            if (category_id != '') {
                var formUrl = basePath + 'controls/getStudents';
                var formType = "POST";
                var formData = {'category_id' : category_id};
                $.ajax({
                    type: formType,
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        response = JSON.parse(data);
                        students = '';
                        $('#message').html("");
                        $('#students').html('');
                        $('#div_students').attr('hidden',true);
                        $('#div_btn').attr('hidden',true);
                        if (response.length > 0) {
                            $('#div_students').removeAttr('hidden');
                            $('#div_btn').removeAttr('hidden');
                            for (var i = 0; i < response.length; i++) {
                                students += '<tr>'+
                                                '<td>'+response[i].s.code+'</td>'+
                                                '<td>'+response[i].s.name +' '+response[i].s.lastname+'</td>'+
                                                '<td>'+response[i].s.age+'</td>'+
                                                '<td>'+response[i].s.status_str+'</td>'+
                                                '<td><input type="checkbox" id="assistance_'+response[i].s.id+'" name="assistance_'+response[i].s.id+'" class="checkbox" checked /></td>'+
                                                '<td><textarea id="observation_'+response[i].s.id+'" name="observation_'+response[i].s.id+'" class="form-control" rows="1"></textarea></td>'+
                                            '</tr>';
                            };
                            $('#students').append(students);
                        } else {
                            $('#message').text("Esta categoría no tiene estudiantes asignados.");
                            $('#modalMessage').modal('show');
                        }
                    },
                    error: function(xhr,textStatus,error){
                        $('#message').html("");
                        $('#message').text("Ha ocurrido un error al tratar de buscar los estudiantes de dicha categoría.");
                        $('#modalMessage').modal('show');
                    }
                });
            }
        });

        $('#btnSubmit').click(function() {
           for (var i = 0; i < response.length; i++) {
                var assistance = 1;
                if(!$('#assistance_'+response[i].s.id).is(':checked')) assistance = 0;
                response[i].s.assistance = assistance;
                response[i].s.observation = $('#observation_'+response[i].s.id).val();
           }
           var validatorObj = $('#control_form').data('bootstrapValidator');
                validatorObj.validate();
            if(validatorObj.isValid()){
                verifyAssistanceControl($('#category').val(),$('#date_control').val());
            }
        });

        $('#btnConfirmation').click(function() {
            $('#modalConfirmation').modal('hide');
            $('#modalWait').modal('show');
            for (var i = 0; i < response.length; i++) {
                var assistance = 1;
                if(!$('#assistance_'+response[i].s.id).is(':checked')) assistance = 0;
                response[i].s.assistance = assistance;
                response[i].s.observation = $('#observation_'+response[i].s.id).val();
            }

            var formUrl = basePath + 'controls/add';
            var formType = "POST";
            var formData = {'category_id' : parseInt($('#category').val()), 'students' : response, 'date_control' : $('#date_control').val()};
            
            $.ajax({
                type: formType,
                url: formUrl,
                data: formData,
                success: function(data,textStatus,xhr){
                    $('#modalWait').modal('hide');
                    $('#modalSuccess').modal('show');
                },
                error: function(xhr,textStatus,error){
                    $('#modalWait').modal('hide');
                    $('#message').html("");
                    $('#message').text("Ha ocurrido un error al tratar de guardar la asistencia.");
                    $('#modalMessage').modal('show');
                }
            });

            return false;
        });
    }

    if ($('#form_report_category').length) {
        $('#form_report_category').bootstrapValidator({
            framework: 'bootstrap',
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                date_from: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha'
                        }
                    }
                },
                date_until: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la categoría'
                        }
                    }
                }
            }
        });
        
        $('#date_from_div')
            .on('changeDate show', function(e) {
                $('#form_report_category').bootstrapValidator('revalidateField', 'date_from');
        });

        $('#date_until_div')
            .on('changeDate show', function(e) {
                $('#form_report_category').bootstrapValidator('revalidateField', 'date_until');
        });
        
        $('#category').change(function() {
            $('#student').val('').selectpicker('refresh');
            category_id = $(this).val();
            if (category_id != '') {
                var formUrl = basePath + 'controls/getStudents';
                var formType = "POST";
                var formData = {'category_id' : category_id};
                $.ajax({
                    type: formType,
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        response = JSON.parse(data);
                        if (response.length == 0) {
                            $('#message').text("Esta categoría no tiene estudiantes asignados.");
                            $('#modalMessage').modal('show');
                        }
                    },
                    error: function(xhr,textStatus,error){
                        $('#message').html("");
                        $('#message').text("Ha ocurrido un error al tratar de buscar los estudiantes de dicha categoría.");
                        $('#modalMessage').modal('show');
                    }
                });
            }
        });

        $('#btnReset').click(function() {
            $('#date_from').val('');
            $('#date_until').val('');
            $('#category').val('').selectpicker('refresh');
            $('#form_report_category').data('bootstrapValidator').resetField($('#category'));
            $('#form_report_category').data('bootstrapValidator').resetField($('#date_from'));
            $('#form_report_category').data('bootstrapValidator').resetField($('#date_until'));
        });
    }

    if ($('#form_report_student').length) {
        $('#form_report_student').bootstrapValidator({
            framework: 'bootstrap',
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                date_from: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha'
                        }
                    }
                },
                date_until: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la fecha'
                        }
                    }
                },
                category: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la categoría'
                        }
                    }
                },
                student: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el estudiante'
                        }
                    }
                }
            }
        });
        
        $('#date_from_div')
            .on('changeDate show', function(e) {
                $('#form_report_student').bootstrapValidator('revalidateField', 'date_from');
        });

        $('#date_until_div')
            .on('changeDate show', function(e) {
                $('#form_report_student').bootstrapValidator('revalidateField', 'date_until');
        });

        $('#category').change(function() {
            $('#student').val('').selectpicker('refresh');
            category_id = $(this).val();
            if (category_id != '') {
                var formUrl = basePath + 'controls/getStudents';
                var formType = "POST";
                var formData = {'category_id' : category_id};
                $.ajax({
                    type: formType,
                    url: formUrl,
                    data: formData,
                    success: function(data,textStatus,xhr){
                        response = JSON.parse(data);
                        if (response.length > 0) addItemsSelect(response);
                        else {
                            $('#message').text("Esta categoría no tiene estudiantes asignados.");
                            $('#modalMessage').modal('show');
                        }
                    },
                    error: function(xhr,textStatus,error){
                        $('#message').html("");
                        $('#message').text("Ha ocurrido un error al tratar de buscar los estudiantes de dicha categoría.");
                        $('#modalMessage').modal('show');
                    }
                });
            }
        });

        $('#btnReset').click(function() {
            $('#date_from').val('');
            $('#date_until').val('');
            $('#category').val('').selectpicker('refresh');
            $('#student').val('').selectpicker('refresh');
            $('#form_report_student').data('bootstrapValidator').resetField($('#category'));
            $('#form_report_student').data('bootstrapValidator').resetField($('#student'));
            $('#form_report_student').data('bootstrapValidator').resetField($('#date_from'));
            $('#form_report_student').data('bootstrapValidator').resetField($('#date_until'));
        });
    }

});

function verifyAssistanceControl(category_id,date_control){
    var formUrl = basePath + 'controls/verifyAssistance';
    var formType = "POST";
    var formData = {'category_id' : parseInt(category_id), 'date_control' : date_control};
    
    $.ajax({
        type: formType,
        url: formUrl,
        data: formData,
        success: function(data,textStatus,xhr){
            if (data) {
                $('#modalWait').modal('hide');
                $('#message').html("");
                $('#message').text("La asistencia de la categoría ya ha sido guardada anteriormente.");
                $('#modalMessage').modal('show');
            } else $('#modalConfirmation').modal('show');
        },
        error: function(xhr,textStatus,error){
            $('#modalWait').modal('hide');
            $('#message').html("");
            $('#message').text("Ha ocurrido un error al consultar el control de asistencia.");
            $('#modalMessage').modal('show');
        }
    });

    return false;
}

function addItemsSelect(items) {
    $("#student").html("");
    $("#student")
        .append('<option value="">Seleccione</option>')
        .selectpicker('refresh');

    for(var i in items){
        $("#student")
        .append('<option value="'+items[i].s.id+'">'+items[i].s.name+' '+items[i].s.lastname+'</option>')
        .selectpicker('refresh');
    }
}
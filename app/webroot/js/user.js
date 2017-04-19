$(document).ready(function() {

    if ($('#user_form').length) {
        $('#user_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el nombre del usuario'
                        },
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                        }
                    }
                },
                lastname: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el apellido del usuario'
                        },
                        regexp: {
                           regexp: /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                        }
                    }
                },
                phone: {
                    validators: {
                        regexp: {
                           regexp: /^[0-9]+$/,
                           message: 'Solo se permiten números'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el email del usuario'
                        },
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'Formato incorrecto de email'
                        }
                    }
                },
                username: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique el username del usuario'
                        },
                        regexp: {
                           regexp: /^[a-zA-Z0-9_-]+$/,
                           message: 'Solo se permiten letras, números y - _'
                        }
                    }
                }
            }
        });
    }

    if ($('#change_password_form').length) {
        $('#change_password_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                password_update: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la nueva contraseña'
                        }
                    }
                },
                password_confirmation: {
                    validators: {
                        notEmpty: {
                            message: 'Por favor indique la contraseña de confirmación'
                        },
                        identical: {
                            field: 'password_update',
                            message: 'Las contraseñas no coinciden'
                        }
                    }
                }
            }
        });
    }

    $('#delete_form').submit(function(){
        //serialize form data
        //var formData = $(this).serialize();
        //get form action
        var user = $('#modalDeleteUser').attr('user');
        $("#modalDeleteUser").modal('hide');
        var formUrl = $(this).attr('action');
        var formType = $(this).attr('method');
        var formData = {'user_id' : user};
        $.ajax({
            type: formType,
            url: formUrl,
            data: formData,
            success: function(data,textStatus,xhr){
                $(".message").text("Usuario eliminado satifactoriamente!");
                $("#modalMessage").modal('show');
            },
            error: function(xhr,textStatus,error){
                $(".message").text("Ha ocurrido un error al tratar de eliminar el usuario!");
                $("#modalMessage").modal('show');
            }
        }); 
        
        return false;
    });

    if ($('#role_p').length){
        $('#btnSubmit').click(function(e){
            if($('#role_p').is(':checked')  && $('#teacherInformacion').css('display') == 'none') e.preventDefault();
        });
    }
    
    if ($('#role_p').length){
        if($('#role_p').attr('checked') == 'checked') {
            $('#teacherInformacion').toggle('slow');
            $("#iconInformation").toggleClass("glyphicon glyphicon-chevron-down glyphicon glyphicon-chevron-right ");
       }
    }

    if ($('#role_p').length){
        $('#role_p').click(function() {
            if($(this).is(':checked')) {
                $('#teacherInformacion').toggle('slow');
                $("#iconInformation").toggleClass("glyphicon glyphicon-chevron-down glyphicon glyphicon-chevron-right ");
           }
        });
    }

    if ($('#role_a').length){
        $('#role_a').click(function() {
            if($(this).is(':checked')) {
                if($('#teacherInformacion').css('display') == 'block'){
                    $('#teacherInformacion').toggle('slow');
                    $("#iconInformation").toggleClass("glyphicon glyphicon-chevron-down glyphicon glyphicon-chevron-right ");
                }
           }
        });
    }
    
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

function deleteUser(user) {
    $("#modalDeleteUser").modal('show');
    $("#modalDeleteUser").attr("user", user);  
}
$(document).ready(function(){
    if ($('#client_form').length) {
        $('#client_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                document_type: {
                    validators: {
                        notEmpty: {
                            message: 'Seleccione un tipo de documento'
                        }
                    }
                },
                name: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el nombre del cliente'
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
                           regexp: /^[0-9\/]+$/,
                           message: 'Solo se permiten números'
                        }
                    }
                },
                email: {
                    validators: {
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'Formato incorrecto de email'
                        }
                    }
                }
            }
        });
    }

    if ($('input[type=radio][name=document_type]').length) {
        $('input[type=radio][name=document_type]').change(function() {
            document_type = $('input:radio[name=document_type]:checked').val();
            if (document_type == 'Pasaporte') {
                $('#document_number').attr('maxlength',20);
                $('#client_form').bootstrapValidator('addField', $('#document_number'), {
                        validators: {
                            notEmpty: {
                                message: 'Por favor indique el número de documento'
                            },
                            regexp: {
                               regexp: /^[a-zA-Z0-9]+$/,
                               message: 'Solo se permiten letras y números'
                           }
                        }
                    });
            } else if (document_type == 'Cedula'){
                $('#document_number').attr('maxlength',10);
                $('#client_form').bootstrapValidator('addField', $('#document_number'), {
                        validators: {
                            notEmpty: {
                                message: 'Por favor indique el número de documento'
                            },
                            regexp: {
                               regexp: /^[0-9]{10}$/,
                               message: 'Solo se permiten números, debe colocar 10 dígitos.'
                           }
                        }
                    });
            } else if(document_type == 'RUC'){
                $('#document_number').attr('maxlength',13);
                $('#client_form').bootstrapValidator('addField', $('#document_number'), {
                        validators: {
                            notEmpty: {
                                message: 'Por favor indique el número de documento'
                            },
                            regexp: {
                               regexp: /^[0-9]{13}$/,
                               message: 'Solo se permiten números, debe colocar 13 dígitos.'
                           }
                        }
                    });
            }
            
        });
    }

	$('#delete_form').submit(function(){
		//serialize form data
		//var formData = $(this).serialize();
		//get form action
		var client = $('#modalDeleteClient').attr('client');
		$("#modalDeleteClient").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'people_id' : client};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	            $(".message").text("Cliente eliminado satifactoriamente!");
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar el cliente!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
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

function deleteClient(client) {
    $("#modalDeleteClient").modal('show');
    $("#modalDeleteClient").attr("client", client); 
}
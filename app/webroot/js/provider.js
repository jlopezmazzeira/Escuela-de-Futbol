$(document).ready(function(){
    if ($('#provider_form').length) {
        $('#provider_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                document_number: {
                    validators: {
                        regexp: {
                           regexp: /^[0-9]{13}$/,
                           message: 'Solo se permiten números, debe colocar 13 dígitos.'
                        },
                        notEmpty: {
                            message: 'Por favor indique el número de RUC'
                        }
                    }
                },
                name: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el nombre del proveedor'
                        },
                        regexp: {
                           regexp: /^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s]+$/i,
                           message: 'Solo se permiten letras'
                        }
                    }
                },
                type_provider: {
                    validators: {
                        notEmpty: {
                            message: 'Seleccione un tipo de persona'
                        }
                    }
                },
                type_accounting: {
                    validators: {
                        notEmpty: {
                            message: 'Seleccione un tipo contabilidad'
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

	$('#delete_form').submit(function(){
		//serialize form data
		//var formData = $(this).serialize();
		//get form action
		var provider = $('#modalDeleteProvider').attr('provider');
		$("#modalDeleteProvider").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'people_id' : provider};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	            $(".message").text("Proveedor eliminado satifactoriamente!");
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar el proveedor!");
	            $("#modalMessage").modal('show');
	    	}
		});	
	    
		return false;
	});

	var professional = $('#type_provider').val();
	if (typeof(professional) !== "undefined") {
	    if(professional == 2){

            $("#r2").prop('checked', 'checked');
            $("#r2").prop('disabled', false);
            $("#r1").prop('disabled', true);

        } else if(professional == 4){

            $("#r1").prop('checked', 'checked');
            $("#r1").prop('disabled', false);
            $("#r2").prop('disabled', true);

        }
	}
	
	$('#type_provider').change(function(){
        //Valor 1 - Juridico - Obligado (#r2)
        //Valor 3 - RISE - No Obligado (#r1)

        if($('#type_provider').val() == 2){

            $("#r2").prop('checked', 'checked');
            $("#r2").prop('disabled', false);
            $("#r1").prop('disabled', true);

        } else if($('#type_provider').val() == 4){

            $("#r1").prop('checked', 'checked');
            $("#r1").prop('disabled', false);
            $("#r2").prop('disabled', true);

        } else{

            $("#r1").prop('checked', false);
            $("#r1").prop('disabled', false);
            $("#r2").prop('checked', false);
            $("#r2").prop('disabled', false);

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

function deleteProvider(provider) {
    $("#modalDeleteProvider").modal('show');
    $("#modalDeleteProvider").attr("provider", provider);   
}
$(document).ready(function(){

    if ($('#product_form').length) {
        $('#product_form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                name: {
                    validators: {
                        stringLength: {
                            min: 3,
                        },
                        notEmpty: {
                            message: 'Por favor indique el nombre del producto'
                        }
                    }
                },
                cost: {
                    validators: {
                        numeric: {
                            message: 'No es un valor númerico',
                            // The default separators
                            thousandsSeparator: '',
                            decimalSeparator: '.'
                        },
                        notEmpty: {
                            message: 'Por favor indique el costo del producto'
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
		var product = $('#modalDeleteProduct').attr('product');
		$("#modalDeleteProduct").modal('hide');
		var formUrl = $(this).attr('action');
		var formType = $(this).attr('method');
		var formData = {'product_id' : product};
		$.ajax({
	    	type: formType,
	   	 	url: formUrl,
	    	data: formData,
	    	success: function(data,textStatus,xhr){
	            $(".message").text("Producto eliminado satifactoriamente!");
	            $("#modalMessage").modal('show');
	    	},
	    	error: function(xhr,textStatus,error){
	            $(".message").text("Ha ocurrido un error al tratar de eliminar el producto!");
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

function deleteProduct(product) {
    $("#modalDeleteProduct").modal('show');
    $("#modalDeleteProduct").attr("product", product);  
}
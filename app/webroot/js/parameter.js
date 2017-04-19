$(document).ready(function() {
    $('#parameter_form').bootstrapValidator({
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
                        message: 'Por favor indique el nombre del parámetro'
                    }
                }
            },
            alias: {
                validators: {
                    stringLength: {
                        min: 3,
                    },
                    notEmpty: {
                        message: 'Por favor indique el alias del parámetro'
                    }
                }
            },
            value: {
                validators: {
                    numeric: {
                        message: 'No es un valor númerico',
                        // The default separators
                        thousandsSeparator: '',
                        decimalSeparator: '.'
                    },
                    notEmpty: {
                        message: 'Por favor indique el valor númerico del parámetro'
                    }
                }
            }
        }
    });
});
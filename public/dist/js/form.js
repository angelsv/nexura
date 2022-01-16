
$(document).ready(function() {

    // Parámetros generales para la validación
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'div',
        errorClass: 'invalid-feedback',
        errorPlacement: function(error, element) {
            if(element.parent('.form-check').length) {
                element.parent().parent().append(error);
            } else {
                error.insertAfter(element);
            }
        }
    });

    // Validación formulario
    $("#form-employee").validate({
        rules: {
            "employee[nombre]": {
                required: true,
                minlength: 5,
            },
            "employee[email]": {
                required: true,
                email: true,
                minlength: 5,
            },
            "employee[sexo]": {
                required: true,
            },
            "employee[area_id]": {
                required: true,
            },
            "employee[descripcion]": {
                required: true,
                minlength: 5,
            },
            "employee[roles][]": {
                required: true,
                minlength: 1,
            },
        },
        messages: {
            "employee[nombre]": {
                required: "El campo nombre es obligatorio",
                minlength: "Debe tener al menos 5 caracteres",
            },
            "employee[email]": {
                required: "El campo email es obligatorio",
                email: "Debe incluir un correo con formato válido",
                minlength: "Debe tener al menos 5 caracteres",
            },
            "employee[sexo]": {
                required: "El campo sexo es obligatorio",
            },
            "employee[area_id]": {
                required: "El campo área es obligatorio",
            },
            "employee[descripcion]": {
                required: "El campo descripcion es obligatorio",
                minlength: "Debe tener al menos 5 caracteres",
            },
            "employee[roles][]": "Seleccione al menos un (1) rol del listado",
        },
    });
    
});

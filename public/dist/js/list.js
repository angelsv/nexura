
$(document).ready(function() {

    // Llamado a función eliminar
    $('a.a-delete').on('click', function (event) {
        event.preventDefault();
        Swal.fire({
            title: "Eliminar",
            text: "¿Realmente desea eliminar este registro? Recuerde que la información no se podrá recuperar",
            icon: "warning",
            showCancelButton: true,
            cancelButtonColor: '#3085d6',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
                deleteEmployee($(this).attr('data-id'));
            }
          })
    });
});

// Eliminar
function deleteEmployee(id){
    $.ajax({
        type: "POST",
        url: `/employee/delete`,
        data: {id: id, csrf_token: $('#csrf_token').val()},
        dataType: "json",
        success: function (response) {
            var icon = response.response ? 'success' : 'warning';
            var msg = response.msg ? response.msg : 'Ocurrió un error inesperado. Contacte al administrador';
            Swal.fire(
                'Eliminado!',
                msg,
                icon
            )
            if(response.response){
                $(`#row-${id}`).remove();
                $(`#csrf_token`).val(response.csrf_token);
            }
        }
    });
}
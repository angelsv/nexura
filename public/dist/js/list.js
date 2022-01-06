
$(document).ready(function() {

    // Llamado a función eliminar
    $('a.a-delete').on('click', function (event) {
        event.preventDefault();
        swal({
            title: "¿Realmente desea eliminar este registro?",
            text: "Una vez eliminado, la información no se podrá recuperar!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                deleteEmployee($(this).attr('data-id'));
            }
        });
    });
});

// Eliminar
function deleteEmployee(id){
    $.ajax({
        type: "POST",
        url: `/employee/delete`,
        data: {id: id},
        dataType: "json",
        success: function (response) {
            var icon = response.response ? 'success' : 'warning';
            var msg = response.msg ? response.msg : 'Ocurrió un error inesperado. Contacte al administrador';
            swal(msg, {
                icon: icon,
            });
            if(response.response){
                $(`#row-${id}`).remove();
            }
        }
    });
}
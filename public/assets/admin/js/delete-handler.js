$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();

    var url  = $(this).data('url');
    var name = $(this).data('name') || 'this item';

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete " + name + "!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire('Deleted!', response.message || 'Deleted successfully.', 'success');
                    // remove row from table
                    if ($(e.target).closest('tr').length) {
                        $(e.target).closest('tr').fadeOut(500, function(){ $(this).remove(); });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Could not delete. ' + (xhr.responseJSON?.message || ''), 'error');
                }
            });
        }
    });
});

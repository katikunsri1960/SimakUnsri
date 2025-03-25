function confirmSubmit(formId, message='Apakah Anda Yakin?', confirmButtonText='Ya, Lanjutkan', cancelButtonText='Batal') {
    $(`#${formId}`).submit(function(e){
        e.preventDefault();
        swal({
            title: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText
        }, function(isConfirm){
            if (isConfirm) {
                $('#spinner').show();
                $(`#${formId}`).unbind('submit').submit();
            }
        });
    });
}

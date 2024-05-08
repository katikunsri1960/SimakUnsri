function confirmSubmit(formId) {
    $(`#${formId}`).submit(function(e){
        e.preventDefault();
        swal({
            title: 'Apakah Anda Yakin?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal'
        }, function(isConfirm){
            if (isConfirm) {
                $('#spinner').show();
                $(`#${formId}`).unbind('submit').submit();
            }
        });
    });
}

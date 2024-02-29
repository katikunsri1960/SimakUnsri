function confirmSubmit(formId, title, text, confirmText, cancelText) {
    $(`#${formId}`).submit(function(e){
        e.preventDefault();
        swal({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText
        }, function(isConfirm){
            if (isConfirm) {
                $('#spinner').show();
                $(`#${formId}`).unbind('submit').submit();
            }
        });
    });
}

<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
@if (session('success'))
<script>   
    swal({
        type: 'success',
        title: 'Berhasil !!',
        text: '{{session('success')}}',
    });
</script>
@endif
@if (session('error'))
<script>
    swal({
        type: 'error',
        title: 'Gagal !!',
        text: '{{session('error')}}',
    })
</script>
@endif
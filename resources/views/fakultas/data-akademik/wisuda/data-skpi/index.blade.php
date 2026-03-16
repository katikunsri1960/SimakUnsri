@extends('layouts.fakultas')
@section('title')
Pendaftaran Wisuda Fakultas
@endsection
@section('content')
@include('swal')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
			<div class="box pull-up">
				<div class="box-body bg-img bg-primary-light">
					<div class="d-lg-flex align-items-center justify-content-between">
						<div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
			    			<img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}" class="img-fluid max-w-250" alt="" />
							<div class="ms-30">
								<h2 class="mb-10">Pendaftaran Wisuda Fakultas</h2>
								<p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
							</div>
						</div>
					<div>
				</div>
			</div>							
		</div>
    </div>
    {{-- @include('fakultas.data-akademik.wisuda.data-skpi.detail-mahasiswa') --}}
    <div class="row">
        <div class="col-12">
            <div class="box box-outline-success bs-3 border-success">
                <div class="box-header with-border">
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Prodi</label>
                        <div class="col-md-8">
                            <select name="prodi" id="prodi" required class="form-select">
                                <option value="*">-- Semua Prodi --</option>
                                @foreach ($prodi as $p)
                                <option value="{{$p->id_prodi}}">({{$p->kode_program_studi}}) - {{$p->nama_jenjang_pendidikan}} {{$p->nama_program_studi}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Periode</label>
                        <div class="col-md-3">
                            <select name="periode" id="periode" required class="form-select">
                                @foreach ($periode as $per)
                                <option value="{{$per->periode}}">{{$per->periode}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   <div class="form-group row">
                        <label class="col-form-label col-md-2">&nbsp;</label>
                        <div class="col-md-8">
                           <div class="row mx-2">
                            <button class="btn btn-sm btn-primary" onclick="getData()">Tampilkan <i class="fa fa-magnifying-glass ms-2"></i></button>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="data" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">NO</th>
                                    <th class="text-center align-middle">NIM</th>
                                    <th class="text-center align-middle">NAMA</th>
                                    <th class="text-center align-middle">JENJANG</th>
                                    <th class="text-center align-middle">PROGRAM STUDI</th>
                                    <!-- <th class="text-center align-middle">STATUS</th> -->
                                    <th class="text-center align-middle">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
					  </table>
                    </div>
                </div>
            </div>
        </div>
    </div>			
</section>
@endsection
@push('js')
<script src="{{asset('assets/vendor_components/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
$(document).ready(function () {

    // INIT DATATABLE
    var table = $('#data').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false
    });

});


// FUNCTION AMBIL DATA
function getData()
{
    var prodi = $('#prodi').val();
    var periode = $('#periode').val();

    if (prodi == '' || periode == '') {
        swal('Peringatan', 'Silahkan pilih prodi dan periode terlebih dahulu', 'warning');
        return;
    }

    $.ajax({
        url: `{{ route('fakultas.wisuda.skpi.data') }}`,
        type: 'GET',
        data: {
            prodi: prodi,
            periode: periode
        },

        success: function (response) {
            // console.log(response.data)

            if (response.status === 'success') {

                var table = $('#data').DataTable();
                table.clear();

                $.each(response.data, function (index, item) {

                    table.row.add([
                        `<div class="text-center">${index + 1}</div>`,
                        `<div class="text-center">${item.nim}</div>`,
                        `<div class="text-start">${item.nama_mahasiswa}</div>`,
                        `<div class="text-center">${item.jenjang}</div>`,
                        `<div class="text-start">${item.nama_prodi}</div>`,
                        // `<div class="text-start">${item.skor}</div>`,
                        `
                        <div class="text-center">
                            <a href="{{ route('fakultas.wisuda.skpi.detail', ':id') }}"
                                class="btn btn-sm btn-primary"
                                target="_self"
                                onclick="this.href=this.href.replace(':id','${item.id}')"><i class="fa fa-eye"></i> 
                                    Detail
                            </a>
                        </div>
                        `
                    ]);

                });

                table.draw();

            } else {

                swal('Error', response.message, 'error');

            }
        },

        error: function () {

            swal('Error', 'Gagal mengambil data', 'error');

        }

    });
}


// FUNCTION TAMPILKAN DETAIL MAHASISWA
function showDetail(id)
{
    $.ajax({
        url: `{{ route('fakultas.wisuda.skpi.detail', ['id' => 'ID']) }}`.replace('ID', id),
        type: 'GET',

        success: function(response){

            if(response.status === 'success'){

                $('#nim_detail').text(response.mahasiswa.nim);
                $('#nama_detail').text(response.mahasiswa.nama_mahasiswa);
                $('#prodi_detail').text(
                    response.mahasiswa.nama_jenjang_pendidikan + ' ' +
                    response.mahasiswa.nama_program_studi
                );

                let html = '';

                $.each(response.skpi, function(i, item){

                    html += `
                        <tr>
                            <td>${i+1}</td>
                            <td>${item.nama_kegiatan ?? ''}</td>
                            <td>${item.kegiatan ?? ''}</td>
                            <td>${item.tahun ?? ''}</td>
                            <td>${item.approved}</td>
                        </tr>
                    `;
                });

                $('#table_detail_skpi tbody').html(html);

                $('#detailMahasiswaModal').modal('show');
            }
        }
    });
}
</script>

@endpush


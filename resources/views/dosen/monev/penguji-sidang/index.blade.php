@extends('layouts.dosen')
@section('title')
Monev Pembimbing Karya Ilmiah
@endsection
@section('content')
<section class="content bg-white">
    <div class="row align-items-end">
        <div class="col-12">
            <div class="box pull-up">
                <div class="box-body bg-img bg-primary-light">
                    <div class="d-lg-flex align-items-center justify-content-between">
                        <div class="d-lg-flex align-items-center mb-30 mb-xl-0 w-p100">
                            <img src="{{asset('images/images/svg-icon/color-svg/custom-14.svg')}}"
                                class="img-fluid max-w-250" alt="" />
                            <div class="ms-30">
                                <h2 class="mb-10">Monev Pembimbing Karya Ilmiah</h2>
                                <p class="mb-0 text-fade fs-18">Universitas Sriwijaya</p>
                            </div>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12">
                    <div class="box box-body mb-0 ">
                        {{-- <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <h3 class="fw-500 text-dark mt-0">Daftar Penelitian Dosen</h3>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-6">
                                <label for="nim" class="form-label">Program Studi</label>
                                <div class="input-group mb-3">
                                    <select name="id_prodi" id="id_prodi" class="form-select">
                                        <option value="">-- Pilih Prodi --</option>
                                        @foreach ($prodi as $p)
                                        <option value="{{$p->id_prodi}}">{{$p->nama_jenjang_pendidikan}} -
                                            {{$p->nama_program_studi}}</option>
                                        @endforeach
                                    </select>
                                    <button class="input-group-button btn btn-primary btn-sm" id="basic-addon1"
                                        onclick="getMonev()"><i class="fa fa-search"></i> Proses</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="data-div"></div>
                        </div>
                    </div>
                </div>
            </div>
</section>
@endsection
@push('js')
<script>
    function getMonev() {
        var id_prodi = $('#id_prodi').val();
        $.ajax({
            url: "{{route('dosen.monev.karya-ilmiah.get-data')}}",
            type: "GET",
            data: {
                id_prodi: id_prodi
            },
            success: function (data) {
            //    empty data-div

                $('#data-div').empty();

                if(data.status == 0)
                {
                    $('#data-div').html(`
                        <div class="alert alert-danger" role="alert">
                            <h4>${data.message}</h4>
                        </div>
                    `);
                }
                else
                {
                    // make table from data and append to data-div
                    var table = `
                        <div class="table-responsive">
                            <table id="data" class="table table-hover table-bordered margin-top-10 w-p100">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle">No</th>
                                        <th class="text-center align-middle">NIDN</th>
                                        <th class="text-center align-middle">Dosen</th>
                                        <th class="text-center align-middle">Jumlah<br>Pembimbing Utama</th>
                                        <th class="text-center align-middle">Jumlah<br>Pembimbing Pendamping</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    var baseUrl = "{{ route('dosen.monev.karya-ilmiah.pembimbing-utama', ':id_dosen') }}";
                    baseUrl = baseUrl.replace(':id_dosen', '');

                    var baseUrlPendamping = " {{ route('dosen.monev.karya-ilmiah.pembimbing-pendamping', ':id_dosen') }}";
                    baseUrlPendamping = baseUrlPendamping.replace(':id_dosen', '');

                    data.data.forEach((d, i) => {

                        table += `
                                <tr>
                                    <td class="text-center align-middle">${i + 1}</td>
                                    <td class="text-center align-middle">${d.nidn}</td>
                                    <td class="text-start align-middle">${d.nama_dosen}</td>
                                    <td class="text-center align-middle">
                                       <a href="${baseUrl}${d.id_dosen}">
                                            ${d.pembimbing_utama}
                                        </a>
                                    </td>
                                    <td class="text-center align-middle">
                                         <a href="${baseUrlPendamping}${d.id_dosen}">
                                        ${d.pembimbing_pendamping}
                                    </td>
                                </tr>
                            `;
                        });
                    table += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    $('#data-div').html(table);

                    $('#data').DataTable();


                }
            }
        });
    }

</script>
@endpush

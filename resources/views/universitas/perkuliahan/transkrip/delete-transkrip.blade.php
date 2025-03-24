<div class="row">
    {{-- input dan button proses --}}
    <div class="col-md-12">
        <div class="form-group">
            <label for="id_registrasi_mahasiswa">NIM</label>
            <select class="form-select" name="id_registrasi_mahasiswa" id="id_registrasi_mahasiswa">
                <option selected>-- Masukan NIM / Nama Mahasiswa --</option>
            </select>
        </div>
        <button class="btn btn-primary waves-effect waves-light" id="btnCari"><i class="fa fa-magnifying-glass"></i>
            Tampilkan Data</button>
    </div>
</div>
<div class="row">
    <div class="mt-5">
        <div class="box-body text-center">
            <div class="">
                <div id="krsDiv" hidden>

                    <h3 class="text-center">Transkrip Mahasiswa</h3>
                    <table class="table table-bordered mt-4" id="krs-regular">
                        <thead>
                            <tr>
                                <th class="text-center align-middle">No</th>
                                <th class="text-center align-middle">Kode Mata Kuliah</th>
                                <th class="text-center align-middle">Nama Mata Kuliah</th>
                                <th class="text-center align-middle">SKS</th>
                                <th class="text-center align-middle">Nilai Angka</th>
                                <th class="text-center align-middle">Nilai Index</th>
                                <th class="text-center align-middle">Nilai Huruf</th>
                                <th class="text-center align-middle">ACT</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-center align-middle">Total SKS</th>
                                <th class="text-center align-middle" id="totalSks"></th>
                                <th colspan="4"></th>

                            </tr>
                            <tr>
                                <th colspan="3" class="text-center align-middle">IPK</th>
                                <th class="text-center align-middle" id="ipk"></th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
                    </table>
                    <hr>
                    <div class="row mt-5" id="transferDiv" hidden>
                        <h3>Nilai Transfer</h3>
                        <table class="table table-bordered table-hover mt-2" id="transferTable">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">No</th>
                                    <th class="text-center align-middle">Kode Mata Kuliah</th>
                                    <th class="text-start align-middle">Nama Mata Kuliah</th>
                                    <th class="text-center align-middle">Semester</th>
                                    <th class="text-center align-middle">SKS Diakui</th>
                                    <th class="text-center align-middle">Nilai Index Diakui</th>
                                    <th class="text-center align-middle">Nilai Huruf Diakui</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                    <div class="row mt-5" id="akmDiv">

                    </div>

                    <div class="row mt-5" id="pembayaranDiv">

                    </div>

                    <div class="row mt-5" id="totalDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.min.js')}}"></script>
<script>
    $("#id_registrasi_mahasiswa").select2({
        placeholder : '-- Masukan NIM / Nama Mahasiswa --',
        width: '100%',
        minimumInputLength: 3,
        ajax: {
            url: "{{route('univ.perkuliahan.transkrip.search')}}",
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                // console.log(data);
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.nama_mahasiswa + " ("+item.nim+" - "+item.nama_program_studi+")",
                            id: item.id_registrasi_mahasiswa
                        }
                    })
                };
            },
        }
    });

function showSwal(title, text, type, callback) {
    swal({
        title: title,
        text: text,
        type: type,
        showCancelButton: type === 'warning',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: type === 'warning' ? 'Ya, Hapus!' : 'OK',
        cancelButtonText: 'Batal'
    }, function(isConfirm) {
        if (isConfirm && callback) {
            callback();
        }
    });
}

function deleteTranskrip(id) {
    showSwal('Hapus Data Transkrip', 'Apakah anda yakin ingin menghapus data ini?', 'warning', function() {
        $.ajax({
            url: '{{route('univ.perkuliahan.transkrip.delete')}}',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                console.log(response.status);
                if (response.status === 1) {
                    showSwal('Peringatan!', response.message, 'warning');
                } else {
                    showSwal('Berhasil!', 'Data berhasil dihapus.', 'success');
                    $('#btnCari').click();
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                showSwal('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
            }
        });
    });
}

$(document).ready(function() {
    $('#btnCari').click(function() {
        $("#spinner").show();
        var nim = $('#id_registrasi_mahasiswa').val();
        $('#krs-regular').DataTable().clear().destroy();

        if (nim == '') {
            $("#spinner").hide();
            showSwal('Peringatan', 'NIM / Nama tidak boleh kosong', 'warning');
        } else {
            $.ajax({
                url: '{{route('univ.perkuliahan.transkrip.get')}}',
                type: 'GET',
                data: {
                    nim: nim
                },
                success: function(response) {
                    $("#spinner").hide();
                    if (response.status == 'error') {
                        showSwal('Peringatan!', response.message, 'warning');
                        return false;
                    }

                    $('#krsDiv').removeAttr('hidden');
                    var table = $('#krs-regular').DataTable({
                        paging: false,
                        info: false,
                        columnDefs: [
                            { orderable: false, targets: 0 },
                            { className: 'text-start', targets: 2 }
                        ],
                        order: [],
                        drawCallback: function(settings) {
                            var api = this.api();
                            api.rows({ page: 'current' }).every(function(rowIdx, tableLoop, rowLoop) {
                                var data = this.data();
                                data[0] = rowIdx + 1;
                                this.invalidate();
                            });
                        }
                    });
                    table.clear().draw();
                    var totalSks = 0;
                    var nilai_bobot = 0;
                    var ipk = 0;

                    response.data.forEach(function(krs, index) {
                        var trClass = '';
                        if (krs.nilai_huruf == 'F' || krs.nilai_huruf == null) {
                            trClass = 'bg-danger';
                        }
                        table.row.add([
                            `<td class="text-center align-middle"></td>`,
                            `<td class="text-center align-middle">${krs.kode_mata_kuliah}</td>`,
                            `<td class="text-start align-middle" style="text-align:left !important;">${krs.nama_mata_kuliah}</td>`,
                            `<td class="text-center align-middle">${krs.sks_mata_kuliah}</td>`,
                            `<td class="text-center align-middle">${krs.nilai_angka ?? '-'}</td>`,
                            `<td class="text-center align-middle">${krs.nilai_indeks ?? '-'}</td>`,
                            `<td class="text-center align-middle">${krs.nilai_huruf ?? '-'}</td>`,
                            `<td class="text-center align-middle">
                                <button class="btn btn-danger btn-sm" onclick="deleteTranskrip(${krs.id})"><i class="fa fa-trash"></i></button>
                            </td>`
                        ]).node().className = trClass;

                        var nilai_bobot_temp = parseInt(krs.sks_mata_kuliah) * parseFloat(krs.nilai_indeks);
                        nilai_bobot += nilai_bobot_temp;
                        totalSks += parseInt(krs.sks_mata_kuliah);
                    });

                    table.draw();
                    if (totalSks > 0) {
                        ipk = nilai_bobot / totalSks;
                    }
                    $('#totalSks').text(totalSks);
                    $('#ipk').text(ipk.toFixed(2));
                }
            });
        }
    });
});
</script>
@endpush

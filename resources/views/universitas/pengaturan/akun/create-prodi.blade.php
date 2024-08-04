<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalTitle">
                    Tambah Akun Prodi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('univ.pengaturan.akun.store')}}" method="post" id="createProdiForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="role" value="prodi">
                        <div class="col-md-4 mb-3">
                            <label for="fk_id" class="form-label">Program Studi</label>
                            <select class="form-select" name="fk_id" id="fk_id" required>
                                <option value="">-- Pilih Salah Satu --</option>
                                @foreach ($prodi as $p)
                                    <option value="{{$p->id_prodi}}">{{$p->kode_program_studi}} - {{$p->nama_program_studi}} ({{$p->nama_jenjang_pendidikan}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" required value="{{old('username')}}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" id="name" required value="{{old('name')}}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label">E-Mail</label>
                            <input type="text" class="form-control" name="email" id="email" value="{{old('email')}}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

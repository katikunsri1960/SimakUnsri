<div class="modal fade" id="createFakultas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createFakultasTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFakultasTitle">
                    Tambah Akun Fakultas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('univ.pengaturan.akun.fakultas-store')}}" method="post" id="createFakultasForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="role" value="fakultas">
                        <div class="col-md-4 mb-3">
                            <label for="fk_id" class="form-label">Fakultas</label>
                            <select class="form-select" name="fk_id" id="fakultas_fk_id" required>
                                <option value="">-- Pilih Salah Satu --</option>
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
                            <input type="text" class="form-control" name="email" id="email" required value="{{old('email')}}">
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

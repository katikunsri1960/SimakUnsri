{{--
                    var foto = item.pas_foto ? `
                        <td class="text-center align-middle text-nowrap">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#fotoModal${item.id}">
                                <img src="{{ asset('') }}${item.pas_foto}" alt="Pas Foto" style="width: 150px;" title="Lihat Foto">
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="fotoModal${item.id}" tabindex="-1" aria-labelledby="fotoModalLabel${item.id}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-3">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fotoModalLabel${item.id}">FOTO ${item.nama_mahasiswa}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center m-20">
                                            <img src="{{ asset('') }}${item.pas_foto}" alt="Foto" style="width: 100%; max-width: 500px;" class="rounded-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    ` : '';
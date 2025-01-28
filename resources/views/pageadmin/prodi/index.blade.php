@extends('masteradmin')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h3 class="fw-bold mb-3">Data Program Studi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="/masteradmin">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="/program-studi">Program Studi</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Data Program Studi</a>
                </li>
            </ul>
        </div>
        <div class="card card-round">
            <div class="card-body">
                <button class="btn btn-danger mb-3" id="createNew">Tambah Program Studi</button>
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Program Studi</th>
                            <th>Kode Program Studi</th>
                            <th>Akreditasi</th>
                            <th>Jenjang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Program Studi -->
<div class="modal fade" id="programStudiModal" tabindex="-1" role="dialog" aria-labelledby="programStudiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="programStudiModalLabel">Tambah Program Studi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="programStudiForm">
                    @csrf
                    <input type="hidden" id="programStudiId">
                    <div class="form-group">
                        <label for="nm_prodi">Nama Program Studi</label>
                        <input type="text" class="form-control" id="nm_prodi" name="nm_prodi" required>
                    </div>
                    <div class="form-group">
                        <label for="kd_prodi">Kode Program Studi</label>
                        <input type="text" class="form-control" id="kd_prodi" name="kd_prodi" required>
                    </div>
                    <div class="form-group">
                        <label for="akreditasi">Akreditasi</label>
                        <select class="form-control" id="akreditasi" name="akreditasi" required>
                            <option value="">Pilih Akreditasi</option>
                            <option value="Unggul">Unggul</option>
                            <option value="Baik">Baik</option>
                            <option value="Baik Sekali">Baik Sekali</option>
                            <option value="Belum Terakreditasi">Belum Terakreditasi</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenjang">Jenjang</label>
                        <select class="form-control" id="jenjang" name="jenjang" required>
                            <option value="">Pilih Jenjang</option>
                            <option value="D2">D2</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fakultas_id">Fakultas</label>
                        <select class="form-control" id="fakultas_id" name="fakultas_id" required>
                            <option value="">Pilih Fakultas</option>
                            @foreach($fakultas as $fakultasItem)
                                <option value="{{ $fakultasItem->id }}">{{ $fakultasItem->nm_fakultas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route('prodi.data') }}', // Ganti dengan rute yang sesuai
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'nm_prodi' },
                { data: 'kd_prodi' },
                { data: 'akreditasi' },
                { data: 'jenjang' },
                { data: 'action', orderable: false, searchable: false }
            ],
            createdRow: function(row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + 1);
            }
        });

        $('#createNew').click(function() {
            $('#programStudiForm')[0].reset();
            $('#programStudiId').val('');
            $('#programStudiModalLabel').text('Tambah Program Studi');
            $('#programStudiModal').modal('show');
        });

        $('#programStudiForm').on('submit', function(e) {
            e.preventDefault();

            var id = $('#programStudiId').val();
            var url = id ? '{{ route('programstudi.update', ':id') }}'.replace(':id', id) : '{{ route('programstudi.store') }}';
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#programStudiModal').modal('hide');
                    table.draw();
                    Swal.fire('Sukses!', response.success, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, message) {
                            var input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<span class="text-danger">' + message[0] + '</span>');
                        });
                    } else {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Terjadi kesalahan.', 'error');
                    }
                }
            });
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).data('id');
            var url = '{{ route('programstudi.edit', ':id') }}'.replace(':id', id);

            $.get(url, function(data) {
                $('#programStudiId').val(data.id);
                $('#nm_prodi').val(data.nm_prodi);
                $('#kd_prodi').val(data.kd_prodi);
                $('#akreditasi').val(data.akreditasi);
                $('#jenjang').val(data.jenjang);
                $('#fakultas_id').val(data.fakultas_id);
                $('#programStudiModalLabel').text('Edit Program Studi');
                $('#programStudiModal').modal('show');
            }).fail(function(xhr) {
                Swal.fire('Error!', 'Data tidak ditemukan.', 'error');
            });
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = '{{ route('programstudi.destroy', ':id') }}'.replace(':id', id);

                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.draw();
                            Swal.fire('Terhapus!', response.success, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

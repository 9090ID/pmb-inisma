@extends('masteradmin')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h3 class="fw-bold mb-3">Data Fakultas</h3>
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
                    <a href="/fakultas">Fakultas</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Data Fakultas</a>
                </li>
            </ul>
        </div>
        <div class="card card-round">
            <div class="card-body">
                <button class="btn btn-danger mb-3" id="createNew">Tambah Fakultas</button>
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fakultas</th>
                            <th>Kode Fakultas</th>
                            <th>Akreditasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Tambah/Edit Fakultas -->
<div class="modal fade" id="fakultasModal" tabindex="-1" role="dialog" aria-labelledby="fakultasModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fakultasModalLabel">Tambah Fakultas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fakultasForm">
                    @csrf
                    <input type="hidden" id="fakultasId">
                    <div class="form-group">
                        <label for="nm_fakultas">Nama Fakultas</label>
                        <input type="text" class="form-control" id="nm_fakultas" name="nm_fakultas" required>
                    </div>
                    <div class="form-group">
                        <label for="kode_fakultas">Kode Fakultas</label>
                        <input type="text" class="form-control" id="kode_fakultas" name="kode_fakultas" required>
                    </div>
                    <div class="form-group">
                        <label for="akreditasi">Akreditasi</label>
                        <input type="text" class="form-control" id="akreditasi" name="akreditasi" required>
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
    //  $('#myTable').DataTable();
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: '{{ route('ambildata.data') }}', // Ganti dengan rute yang sesuai
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'nm_fakultas' },
                { data: 'kode_fakultas' },
                { data: 'akreditasi' },
                { data: 'action', orderable: false, searchable: false }
            ],
            createdRow: function(row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + 1);
            }
        });

        $('#createNew').click(function() {
            $('#fakultasForm')[0].reset();
            $('#fakultasId').val('');
            $('#fakultasModalLabel').text('Tambah Fakultas');
            $('#fakultasModal').modal('show');
        });

        $('#fakultasForm').on('submit', function(e) {
            e.preventDefault();

            var id = $('#fakultasId').val();
            var url = id ? '{{ route('fakultas.update', ':id') }}'.replace(':id', id) : '{{ route('fakultas.store') }}';
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#fakultasModal').modal('hide');
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
            var url = '{{ route('fakultas.edit', ':id') }}'.replace(':id', id);

            $.get(url, function(data) {
                $('#fakultasId').val(data.id);
                $('#nm_fakultas').val(data.nm_fakultas);
                $('#kode_fakultas').val(data.kode_fakultas);
                $('#akreditasi').val(data.akreditasi);
                $('#fakultasModalLabel').text('Edit Fakultas');
                $('#fakultasModal').modal('show');
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
                    var url = '{{ route('fakultas.destroy', ':id') }}'.replace(':id', id);

                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            table.draw();

                    // Tampilkan notifikasi sukses
                    Swal.fire('Terhapus!', response.success, 'success');
                },
                error: function(xhr) {
                    // Tangani error dari server
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
                }
                    });
                }
            });
        });
        
    });
</script>
@endpush
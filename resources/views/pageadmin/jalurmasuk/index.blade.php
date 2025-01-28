@extends('masteradmin')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h3 class="fw-bold mb-3">Jalur Masuk PMB INISMA</h3>
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
                    <a href="/jalurmasuk">Jalur Masuk</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Data Jalur Masuk</a>
                </li>
            </ul>
        </div>
        <div class="card card-round">
        <div class="card-body">
            <button class="btn btn-danger mb-3" id="createNew">Tambah Jalur Masuk</button>
        <table id="myTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Jalur/Kelas</th>
                    <th>Tahun</th>
                    <th>Biaya Pendaftaran</th>
                    <th>Mulai Pendaftaran</th>
                    <th>Selesai Pendaftaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
        </div>
        
    </div>
</div>
</div>

<!-- Modal untuk Tambah/Edit Jalur Masuk -->
<div class="modal fade" id="jalurMasukModal" tabindex="-1" role="dialog" aria-labelledby="jalurMasukModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jalurMasukModalLabel">Tambah Jalur Masuk/Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="jalurMasukForm">
                    @csrf
                    <input type="hidden" id="jalurMasukId">
                    <div class="form-group">
                        <label for="nm_jalur">Nama Jalur/Kelas</label>
                        <input type="text" class="form-control" id="nm_jalur" name="nm_jalur" required>
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" required>
                    </div>
                    <div class="form-group">
                        <label for="biaya_pendaftaran">Biaya Pendaftaran</label>
                        <input type="text" class="form-control" id="biaya_pendaftaran" name="biaya_pendaftaran" required>
                    </div>
                    <div class="form-group">
                        <label for="mulai_pendaftaran">Mulai Pendaftaran</label>
                        <input type="date" class="form-control" id="mulai_pendaftaran" name="mulai_pendaftaran" required>
                    </div>
                    <div class="form-group">
                        <label for="selesai_pendaftaran">Selesai Pendaftaran</label>
                        <input type="date" class="form-control" id="selesai_pendaftaran" name="selesai_pendaftaran" required>
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
            resposive: true,
            // scrollX: true,
            ajax: '{{ route('tampilkan.data') }}',
            columns: [
                { data: null, orderable: false, searchable: false },
                { data: 'nm_jalur' },
                { data: 'tahun' },
                { data: 'biaya_pendaftaran' },
                { data: 'mulai_pendaftaran' },
                { data: 'selesai_pendaftaran' },
                { data: 'action', orderable: false, searchable: false }
            ],
            createdRow: function(row, data, dataIndex) {
                $('td:eq(0)', row).html(dataIndex + 1);
            }
        });

        $('#createNew').click(function() {
            $('#jalurMasukForm')[0].reset();
            $('#jalurMasukId').val('');
            $('#jalurMasukModalLabel').text('Tambah Jalur Masuk');
            $('#jalurMasukModal').modal('show');
        });

        $('#jalurMasukForm').on('submit', function(e) {
    e.preventDefault();

    var id = $('#jalurMasukId').val();
    var url = id ? '{{ route('jalurmasuk.update', ':id') }}'.replace(':id', id) : '{{ route('jalurmasuk.store') }}';
    var method = id ? 'PUT' : 'POST';

    // Hapus pesan error sebelumnya
    $('.text-danger').remove();

    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize(),
        success: function(response) {
            // Tutup modal jika berhasil
            $('#jalurMasukModal').modal('hide');

            // Reset form
            $('#jalurMasukForm')[0].reset();

            // Perbarui DataTables
            table.draw();

            // Tampilkan notifikasi sukses
            Swal.fire('Sukses!', response.success, 'success');
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                // Jika validasi gagal, tampilkan pesan error di bawah input
                var errors = xhr.responseJSON.errors;

                $.each(errors, function(key, message) {
                    // Cari elemen input berdasarkan name dan tambahkan pesan error
                    var input = $('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<span class="text-danger">' + message[0] + '</span>');
                });
            } else {
                // Tampilkan pesan error umum
                Swal.fire('Error!', xhr.responseJSON.message || 'Terjadi kesalahan.', 'error');
            }
        }
    });
});

$(document).on('click', '.edit', function() {
    var id = $(this).data('id'); // Ambil ID dari data-id

    // Pastikan URL dibangun dengan benar tanpa "//"
    var url = '{{ route('jalurmasuk.edit', ':id') }}'.replace(':id', id);

    // AJAX request untuk mengambil data berdasarkan ID
    $.get(url, function(data) {
        // Isi form modal dengan data yang diterima
        $('#jalurMasukId').val(data.id);
        $('#nm_jalur').val(data.nm_jalur);
        $('#tahun').val(data.tahun);
        $('#biaya_pendaftaran').val(data.biaya_pendaftaran);
        $('#mulai_pendaftaran').val(data.mulai_pendaftaran);
        $('#selesai_pendaftaran').val(data.selesai_pendaftaran);

        // Ubah judul modal
        $('#jalurMasukModalLabel').text('Edit Jalur Masuk');

        // Hapus pesan error sebelumnya
        $('.text-danger').remove();
        $('.is-invalid').removeClass('is-invalid');

        // Tampilkan modal
        $('#jalurMasukModal').modal('show');
    }).fail(function(xhr) {
        // Debugging untuk melihat error dari server
        console.error('Error fetching data:', xhr);

        // Tampilkan pesan error ke pengguna
        Swal.fire('Error!', 'Data tidak ditemukan.', 'error');
    });
});



$(document).on('click', '.delete', function() {
    var id = $(this).data('id'); // Ambil ID dari data-id

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
            // URL menggunakan route dengan placeholder :id
            var url = '{{ route('jalurmasuk.destroy', ':id') }}'.replace(':id', id);

            // Kirim permintaan AJAX
            $.ajax({
                url: url,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Tambahkan CSRF token
                },
                success: function(response) {
                    // Perbarui tabel setelah penghapusan
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

        $('#myTable').DataTable();
    });
</script>
@endpush
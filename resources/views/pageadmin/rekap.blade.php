@extends('masteradmin')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <h3 class="fw-bold mb-3">Rekapitulasi Pendaftar INISMA</h3>
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
                    <a href="/rekap">Rekapitulasi</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Data Pendaftar</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Data Pendaftar</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th> <!-- Kolom untuk nomor urut -->
                                    <th>Nomor Registrasi</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Status Pembayaran</th>
                                    <th>Total Pembayaran</th> 
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    // Inisialisasi DataTable untuk tabel pendaftar
    $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('pendaftar.data') }}',
        columns: [
            { data: null, name: 'nomor', orderable: false, searchable: false }, // Kolom untuk nomor urut
            { data: 'nomor_registrasi', name: 'nomor_registrasi' },
            { data: 'namalengkap', name: 'namalengkap' },
            { data: 'tanggallahir', name: 'tanggallahir' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'total_price', name: 'total_price' }
        ],
        createdRow: function(row, data, dataIndex) {
            // Tambahkan nomor urut
            $('td:eq(0)', row).html(dataIndex + 1); // Menambahkan nomor urut di kolom pertama
            }
    

    });

    // Jika Anda ingin menginisialisasi tabel lain, misalnya myTable
    $('#myTable').DataTable(); // Pastikan tabel ini ada di dalam HTML Anda
});
</script>
@endpush
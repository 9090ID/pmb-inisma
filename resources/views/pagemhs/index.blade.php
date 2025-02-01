@extends('mahasiswa')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Dashboard</h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                                    <li class="breadcrumb-item"><a href="/pmb-mahasiswa">Dashboard</a></li>
                                </ol>
                            </nav>
                            Welcome, {{ Auth::user()->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-4">Silahkan Anda Ikuti Proses Pendaftaran Mahasiswa Baru</h5>
                    @if ($orders->isEmpty())
                        <p>Belum ada order yang dibuat.</p>
                    @else
                        @foreach ($orders as $order)
                            <div class="order-item">
                                @if ($order->pendaftaran)
                                    <table class="table table-bordered">
                                        <tr class="table-danger">
                                            <td colspan="3"><strong><b>Informasi Detail Pendaftaran</b></strong></td>
                                        <tr>
                                            <td><strong>Nomor Registrasi</strong></td>
                                            <td>:</td>
                                            <td>{{ $order->pendaftaran->nomor_registrasi }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nama Lengkap</strong></td>
                                            <td>:</td>
                                            <td>{{ $order->pendaftaran->namalengkap }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nomor HP</strong></td>
                                            <td>:</td>
                                            <td>{{ $order->pendaftaran->nomorhp }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Program Studi yang Anda Pilih</strong></td>
                                            <td>:</td>
                                            <td>{{ $order->pendaftaran->prodiyangdipilih }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kelas yang Anda Pilih</strong></td>
                                            <td>:</td>
                                            <td>{{ $order->pendaftaran->jalurmasuk->nm_jalur ?? 'N/A' }} -
                                                @if ($order->payment_status == 'Sudah Dibayar')
                                                    <span
                                                        class="badge bg-success">({{ number_format($order->pendaftaran->jalurmasuk->biaya_pendaftaran ?? 'N/A') }})
                                                        - Sudah Lunas</span>
                                                @elseif($order->payment_status == 'Gratis')
                                                    <span class="badge bg-success">Tidak Pakai Biaya</span>
                                                @else
                                                    ({{ number_format($order->pendaftaran->jalurmasuk->biaya_pendaftaran ?? 'N/A') }})
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                <div class="timeline">
                                    <strong>Alur Seleksi Anda, {{ Auth::user()->name }}</strong>
                                
                                    <!-- Timeline Pembayaran -->
                                    <div class="timeline-item {{ $order->payment_status == 'Sudah Dibayar' || $order->payment_status == 'Gratis' ? 'completed' : '' }}">
                                        <div class="timeline-content">
                                            <h6>Pembayaran</h6>
                                            @if ($order->payment_status == 'Gratis')
                                                <span class="badge bg-success">Anda Gratis Biaya Masuk</span>
                                            @else
                                                <p>Order ID: {{ $order->number }}</p>
                                                <p>Status Pembayaran: <b class="text-info">{{ $order->payment_status }}</b></p>
                                                <p>Total Pembayaran: Rp.{{ number_format($order->total_price) }}</p>
                                            @endif
                                            
                                            @if ($order->payment_status == 'Menunggu Pembayaran')
                                                <button class="btn btn-primary" onclick="pay('{{ $order->snap_token }}', '{{ $order->number }}')">Bayar Sekarang</button>
                                            @elseif($order->payment_status == 'Kadaluarsa')
                                                <form action="{{ route('regenerate_payment') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $order->id }}">
                                                    <button type="submit" class="btn btn-warning">Buat Pembayaran Baru</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <!-- Timeline Pengisian Identitas -->
                                    <div class="timeline-item {{ ($order->payment_status == 'Sudah Dibayar' || $order->payment_status == 'Gratis') ? 'completed' : 'disabled' }}">
                                        <div class="timeline-content">
                                            <h6>Pengisian Identitas</h6>
                                            @if ($order->identity_status == 'Sudah Diisi')
                                                <span class="badge bg-success">Identitas Sudah Diisi</span>
                                            @else
                                                <p>Status: <b class="text-info">Belum Diisi</b></p>
                                                <a href="/identitas" class="btn btn-primary" {{ $order->payment_status == 'Menunggu Pembayaran' ? 'disabled' : '' }}>Isi Identitas</a>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <!-- Timeline Tes Ujian Masuk -->
                                    <div class="timeline-item {{ $order->identity_status == 'Sudah Diisi' ? 'completed' : 'disabled' }}">
                                        <div class="timeline-content">
                                            <h6>Tes Ujian Masuk</h6>
                                            @if ($order->exam_status == 'Selesai')
                                                <span class="badge bg-success">Tes Ujian Masuk Selesai</span>
                                            @else
                                                <p>Status: <b class="text-info">Belum Dikerjakan</b></p>
                                                <a href="#!" class="btn btn-primary" {{ $order->identity_status != 'Sudah Diisi' ? 'disabled' : '' }}>Mulai Ujian</a>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <!-- Timeline Hasil Tes/Pengumuman -->
                                    <div class="timeline-item {{ $order->exam_status == 'Selesai' ? 'completed' : 'disabled' }}">
                                        <div class="timeline-content">
                                            <h6>Hasil Tes/Pengumuman</h6>
                                            @if ($order->result_status == 'Diumumkan')
                                                <span class="badge bg-success">Hasil Tes Sudah Diumumkan</span>
                                                <p>Hasil: <b class="text-info">{{ $order->result }}</b></p>
                                            @else
                                                <p>Status: <b class="text-info">Belum Diumumkan</b></p>
                                            @endif
                                        </div>
                                    </div>
                                
                                    <!-- Timeline Selesai -->
                                    <div class="timeline-item {{ $order->result_status == 'Diumumkan' ? 'completed' : 'disabled' }}">
                                        <div class="timeline-content">
                                            <h6>Selesai</h6>
                                            @if ($order->final_status == 'Selesai')
                                                <span class="badge bg-success">Proses Seleksi Selesai</span>
                                            @else
                                                <p>Status: <b class="text-info">Belum Selesai</b></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>



                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Kk0iIsDnj51sHt7l"></script>
    <script>
        function pay(snapToken, orderNumber) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log(result);
                    alert('Pembayaran berhasil!');
                },
                onPending: function(result) {
                    console.log(result);
                    alert('Pembayaran tertunda!');
                },
                onError: function(result) {
                    console.log(result);
                    alert('Pembayaran gagal!');
                }
            });
        }

        $(document).ready(function() {
            $('#regenerate-payment-btn').click(function() {
                var orderId = $(this).data('order-id'); // Use the `id` as the order identifier

                $.ajax({
                    url: '{{ route('regenerate_payment') }}', // Correct route URL
                    type: 'POST', // Use POST method
                    data: {
                        _token: '{{ csrf_token() }}', // CSRF token for security
                        id: orderId, // Send the `id` instead of `order_id`
                    },
                    success: function(response) {
                        alert('Pembayaran baru berhasil dibuat!');
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan, coba lagi nanti.');
                    }
                });
            });
        });
        //       
    </script>
@endpush

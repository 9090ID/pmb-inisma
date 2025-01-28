@extends('mahasiswa')

@section('content')
 
    <div class="row">
        <div class="col-lg-12 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Dashboard</h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                                    <li class="breadcrumb-item active" aria-current="page">Home</li>
                                    <li class="breadcrumb-item"><a href="/pmb-mahasiswa">Dashboard</a></li>
                                    {{-- <li class="breadcrumb-item active" aria-current="page">Data</li> --}}
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
                                        <tr  class="table-danger">
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
                                            <td>{{ $order->pendaftaran->jalurmasuk->nm_jalur ?? 'N/A' }}</td> 
                                        </tr>
                                    </table>
                                @endif

                                <div class="timeline">
                                   <strong>Alur Seleksi Anda, {{ Auth::user()->name }} </strong>
                                    <div
                                        class="timeline-item {{ $order->payment_status == 'Sudah Dibayar' ? 'completed' : '' }}">
                                        <div class="timeline-content">
                                            <h6>Pembayaran</h6>
                                            @if ($order->payment_status == 'Menunggu Pembayaran')
                                            <p>Order ID: {{ $order->number }}</p>
                                            <p>Status Pembayaran: <b class="text-info">{{ $order->payment_status }}</b></p>
                                            <p>Total Pembayaran: Rp.{{ number_format($order->total_price) }}</p>
                                        
                                            <button class="btn btn-primary" onclick="pay('{{ $order->snap_token }}', '{{ $order->number }}')">Bayar Sekarang</button>
                                        
                                        @elseif($order->payment_status == 'Sudah Dibayar')
                                            <a href="{{ route('payment.receipt', $order->number) }}" class="btn btn-danger">Download Bukti Pembayaran</a>
                                        
                                        @elseif($order->payment_status == 'Kadaluarsa')
                                            <p>Status Pembayaran: <b class="text-danger">{{ $order->payment_status }}</b></p>
                                            <p>Status sudah kadaluarsa. Silakan hubungi admin untuk bantuan lebih lanjut.</p>
                                            @elseif($order->payment_status == 'Dikembalikan')
                                            <p>Status Pembayaran: <b class="text-danger">{{ $order->payment_status }}</b></p>
                                            <p>Status sudah refund.</p>
                                            @elseif($order->payment_status == 'Ditolak')
                                            <p>Status Pembayaran: <b class="text-danger">{{ $order->payment_status }}</b></p>
                                            <p>Status sudah ditolak.</p>
                                            @elseif($order->payment_status == 'dibatalkan')
                                            <p>Status Pembayaran: <b class="text-danger">{{ $order->payment_status }}</b></p>
                                            <p>Status sudah ditolak ADMIN INISMA.</p>
                                        @endif
                                        </div>
                                    </div>
                                    <div
                                        class="timeline-item {{ $order->payment_status == 'Sudah Dibayar' ? 'completed' : '' }}">
                                        <div class="timeline-content">
                                            <h6>Isi Identitas</h6>
                                            @if ($order->payment_status == 'Sudah Dibayar')
                                                <p>Silakan isi Identitas Anda</p>
                                            @else
                                                <p>Menunggu pembayaran Pengisian Data.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="timeline-item {{ $order->details_data ? 'completed' : '' }}">
                                        <div class="timeline-content">
                                            <h6>Tes Masuk</h6>
                                            @if ($order->details_data)
                                                <p>Hasil tes: {{ $order->details_data }}</p>
                                            @else
                                                <p>Identitas belum diisi.</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="timeline-item {{ $order->tes_hasil ? 'completed' : '' }}">
                                        <div class="timeline-content">
                                            <h6>Hasil Tes</h6>
                                            @if ($order->tes_hasil)
                                                <p>Hasil tes: {{ $order->tes_hasil }}</p>
                                            @else
                                                <p>Hasil tes belum tersedia.</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item {{ $order->status == 'Selesai' ? 'completed' : '' }}">
                                        <div class="timeline-content">
                                            <h6>Selesai</h6>
                                            <p>{{ $order->status == 'Selesai' ? 'Proses selesai.' : 'Proses belum selesai.' }}
                                            </p>
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
                // Status pembayaran akan diperbarui otomatis oleh Midtrans melalui notifikasi ke server
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
</script>

@endpush

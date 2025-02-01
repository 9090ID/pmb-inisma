<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;
use Yajra\DataTables\Facades\DataTables;

class RekapController extends Controller
{
    public function index()
    {
        return view('pageadmin.rekap');
    }
    public function getPendaftar(Request $request)
    {
        $pendaftar = Pendaftaran::with('orders') // Mengambil data pendaftar dengan relasi orders
            ->select('pendaftarans.*') // Ambil semua kolom dari pendaftarans
            ->get();

        return DataTables::of($pendaftar)
        ->addColumn('payment_status', function ($pendaftar) {
            // Ambil status pembayaran dari order yang pertama (jika ada)
            $order = $pendaftar->orders->first();
            $status = $order ? $order->payment_status : 'Menunggu Pembayaran';
    
            // Mengembalikan status sebagai badge
            switch ($status) {
                case 'Menunggu Pembayaran':
                    return '<span class="badge bg-danger">Menunggu Pembayaran</span>';
                case 'Sudah Dibayar':
                    return '<span class="badge bg-primary">Sudah Dibayar</span>';
                case 'Kadaluarsa':
                    return '<span class="badge bg-warning">Kadaluarsa</span>';
                default:
                    return '<span class="badge bg-secondary">Tidak ada Pembayaran </span>';
            }
        })
        ->addColumn('total_price', function ($pendaftar) {
            // Ambil status pembayaran dari order yang pertama (jika ada)
            $order = $pendaftar->orders->first();
            $status = $order ? $order->payment_status : 'Menunggu Pembayaran';
    
            // Jika status adalah 'Kadaluarsa' atau 'Menunggu Pembayaran', kembalikan 0
            if ($status === 'Gratis') {
                return 'Rp. 0.00'; // Tampilkan 0 jika kadaluarsa atau menunggu pembayaran
            }
    
            // Jika sudah dibayar, hitung total harga dari semua pesanan
            $totalPrice = $pendaftar->orders->sum('total_price'); // Ganti 'total_price' dengan nama kolom yang sesuai di tabel orders
            return 'Rp. ' . number_format($totalPrice); 
        })
        ->rawColumns(['payment_status']) // Pastikan untuk mengizinkan HTML
        ->make(true);
    }
}

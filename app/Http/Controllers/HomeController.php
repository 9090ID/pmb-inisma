<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;
use App\Services\MidtransService;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }
    
    public function masterAdmin()
    {
        return view('pageadmin.index');
    }
    
    public function admin()
    {
        return view('admin');
    }
    
    public function mahasiswa()
    {
        $user = auth()->user();

    // Ambil semua order yang terkait dengan pengguna yang sedang login
    $orders = Order::with(['pendaftaran.jalurmasuk'])
    ->where('user_id', $user->id)
    ->select('id', 'user_id', 'snap_token', 'payment_status', 'expires_at') // Pastikan snap_token disertakan
    ->get();

    foreach ($orders as $order) {
        // Cek jika order sudah kadaluarsa
        if ($order->expires_at && $order->expires_at < now()) {
            $order->update(['payment_status' => 'Kadaluarsa']);
        }

        // Siapkan token Snap untuk order yang masih pending
        if ($order->payment_status == 'Menunggu Pembayaran' && !$order->snap_token) {
            // Konfigurasi Midtrans
            MidtransService::configure();

            $pendaftaran = $order->pendaftaran;
            $jalurMasuk = $pendaftaran->jalurmasuk;

            // Validasi jika data tidak ditemukan
            if (!$pendaftaran || !$jalurMasuk) {
                continue; // Lewatkan order ini jika data tidak lengkap
            }

            // Buat transaksi dengan informasi yang sesuai
            $transactionDetails = [
                'order_id' => $order->number,
                'gross_amount' => $jalurMasuk->biaya_pendaftaran,
            ];

            $itemDetails = [
                [
                    'id' => $pendaftaran->nomor_registrasi,
                    'price' => $jalurMasuk->biaya_pendaftaran,
                    'quantity' => 1,
                    'name' => $jalurMasuk->nm_jalur,
                ],
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $pendaftaran->nomorhp,
                ],
            ];

            // Dapatkan token pembayaran
            $order->snap_token = Snap::getSnapToken($transactionData);
            $order->save();
        }
    }

    return view('pagemhs.index', compact('orders'));
    }
}

    


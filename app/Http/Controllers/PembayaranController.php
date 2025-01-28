<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-bfk8clRtIGPqE8xfhuf5jziX';
        Config::$isProduction = false; // Ubah ke true jika sudah di produksi

        // Buat transaksi
        $transactionDetails = [
            'order_id' => uniqid(),
            'gross_amount' => 100000, // Jumlah pembayaran
        ];

        $itemDetails = [
            [
                'id' => 'item1',
                'price' => 100000,
                'quantity' => 1,
                'name' => 'Pendaftaran Siswa',
            ],
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->user->name,
                'email' => $request->user->email,
            ],
        ];

        // Dapatkan URL pembayaran
        $snapToken = Snap::getSnapToken($transactionData);

        return view('pembayaran', compact('snapToken'));
    }
}
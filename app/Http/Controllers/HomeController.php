<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Pendaftaran;
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

        // Ambil semua pendaftaran pengguna yang belum memiliki order
        $pendaftarans = Pendaftaran::with('jalurmasuk')
            ->where('user_id', $user->id)
            ->doesntHave('orders')
            ->get();

        foreach ($pendaftarans as $pendaftaran) {
            $jalurMasuk = $pendaftaran->jalurmasuk;

            // Validasi jika data tidak ditemukan
            if (!$jalurMasuk) {
                continue; // Lewatkan jika data tidak lengkap
            }

            // Kondisi jika biaya pendaftaran adalah 0
            if ($jalurMasuk->biaya_pendaftaran == 0) {
                continue; // Lewatkan proses pembayaran jika biaya pendaftaran 0
            }

            // Konfigurasi Midtrans
            Config::$serverKey = 'SB-Mid-server-bfk8clRtIGPqE8xfhuf5jziX';  // Server Key

            // Buat transaksi dengan informasi yang sesuai
            $transactionDetails = [
                'order_id' => uniqid(),
                'gross_amount' => $jalurMasuk->biaya_pendaftaran, // Biaya pendaftaran
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
            $snapToken = Snap::getSnapToken($transactionData);

            // Simpan order baru
            Order::create([
                'user_id' => $user->id,
                'pendaftaran_id' => $pendaftaran->id,
                'number' => $transactionDetails['order_id'],
                'snap_token' => $snapToken,
                'payment_status' => 'Menunggu Pembayaran',
                'total_price' => $jalurMasuk->biaya_pendaftaran, // Ambil biaya langsung dari JalurMasuk
                'expires_at' => now()->addHours(24),
            ]);
        }

        // Ambil semua order pengguna
        $orders = Order::with(['pendaftaran.jalurmasuk'])
            ->where('user_id', $user->id)
            ->get();

        return view('pagemhs.index', compact('orders'));
    }


    public function regeneratePayment(Request $request)
    {
        $order = Order::findOrFail($request->id);

        if (!$order->pendaftaran || !$order->pendaftaran->jalurmasuk) {
            return back()->with('error', 'Data pendaftaran tidak lengkap.');
        }

        // Konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-bfk8clRtIGPqE8xfhuf5jziX';

        $transactionDetails = [
            'order_id' => uniqid(),
            'gross_amount' => $order->pendaftaran->jalurmasuk->biaya_pendaftaran,
        ];

        $itemDetails = [
            [
                'id' => $order->pendaftaran->nomor_registrasi,
                'price' => $order->pendaftaran->jalurmasuk->biaya_pendaftaran,
                'quantity' => 1,
                'name' => $order->pendaftaran->jalurmasuk->nm_jalur,
            ],
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->pendaftaran->nomorhp,
            ],
        ];

        // Generate snap token baru
        $snapToken = Snap::getSnapToken($transactionData);

        // Update order dengan snap_token baru
        $order->update([
            'number' => $transactionDetails['order_id'],
            'snap_token' => $snapToken,
            'payment_status' => 'Menunggu Pembayaran',
            'expires_at' => now()->addHours(24),
        ]);

        return back()->with('success', 'Pembayaran baru telah dibuat. Silakan lanjutkan pembayaran.');
    }
}

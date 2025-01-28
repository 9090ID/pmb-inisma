<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use App\Services\CallbackService;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class PaymentCallbackController extends Controller
{
    public function notification(Request $request)
    {
        // Konfigurasi Midtrans
    MidtransService::configure();

    // Tambahkan log untuk debugging
    Log::info('Midtrans Notification:', $request->all());

    try {
        // Ambil notifikasi dari Midtrans
        $notification = new \Midtrans\Notification();

        // Ambil detail transaksi
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $fraudStatus = $notification->fraud_status;

        // Cari order berdasarkan order_id
        $order = Order::where('number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // Perbarui status pembayaran
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $order->payment_status = 'Pending (Challenge)';
            } else {
                $order->payment_status = 'Sudah Dibayar';
            }
        } elseif ($transactionStatus == 'settlement') {
            $order->payment_status = 'Sudah Dibayar';
        } elseif ($transactionStatus == 'pending') {
            $order->payment_status = 'Menunggu Pembayaran';
        } elseif ($transactionStatus == 'cancel') {
            $order->payment_status = 'Dibatalkan';
        } elseif ($transactionStatus == 'expire') {
            $order->payment_status = 'Kadaluarsa';
        } elseif ($transactionStatus == 'deny') {
            $order->payment_status = 'Ditolak';
        } elseif ($transactionStatus == 'refund') {
            $order->payment_status = 'Dikembalikan';
        }

        $order->save();

        return response()->json(['message' => 'Notifikasi berhasil diproses']);
    } catch (\Exception $e) {
        Log::error('Midtrans Notification Error:', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'Terjadi kesalahan saat memproses notifikasi'], 500);
    }
    }


    public function downloadReceipt($order_number)
    {
        // Temukan order berdasarkan nomor
        $order = Order::where('number', $order_number)->firstOrFail();

        // Pastikan status pembayaran adalah 'Dibayar'
        if ($order->payment_status !== 'Sudah Dibayar') {
            return redirect()->back()->with('error', 'Bukti pembayaran tidak tersedia.');
        }

        // Buat PDF atau format lain untuk bukti pembayaran
        // Misalnya, jika Anda menggunakan library PDF
        $pdf = PDF::loadView('pagemhs.payment_receipt', compact('order'));

        // Kembalikan PDF sebagai unduhan
        return $pdf->download('bukti_pembayaran_' . $order->number . '.pdf');
    }
}


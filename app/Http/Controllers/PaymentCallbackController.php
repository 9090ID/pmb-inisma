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
       $payload = $request->all();

// Log the incoming notification payload
Log::info('Incoming Midtrans Notification:', [
    'payload' => $payload
]);

$orderId = $payload['order_id'];
$statusCode = $payload['status_code'];
$grossAmount = $payload['gross_amount'];
$reqSignature = $payload['signature_key'];

// Log the details for signature validation
Log::info('Validating signature:', [
    'orderId' => $orderId,
    'statusCode' => $statusCode,
    'grossAmount' => $grossAmount
]);

// Generate the signature from the payload and compare it with the received signature
   $signature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));


// Log signature comparison
Log::info('Signature Comparison:', [
    'generated_signature' => $signature,
    'received_signature' => $reqSignature
]);

// If the signatures don't match, return an error
if ($signature != $reqSignature) {
    Log::error('Invalid signature for Order ID', ['orderId' => $orderId]);
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid signature'
    ], 401);
}

// Extract transaction status
$transactionStatus = $payload['transaction_status'];
Log::info('Transaction Status:', ['transactionStatus' => $transactionStatus]);

// Look for the order in the database using 'number' field
$order = Order::where('number', $orderId)->first();

// If order is not found, log and return an error
if(!$order){
    Log::error('Order not found for Order ID', ['orderId' => $orderId]);
    return response()->json([
        'status' => 'error',
        'message' => 'Invalid Order'
    ], 400);
}

// Log the order retrieval
Log::info('Order found:', ['order' => $order]);

// Update the order status based on transaction status
if ($transactionStatus == 'settlement') {
    $order->payment_status = 'Sudah Dibayar';
    $order->save();
    Log::info('Order updated to "Sudah Dibayar"', ['orderId' => $orderId]);
} elseif ($transactionStatus == 'expire') {
    $order->payment_status = 'Kadaluarsa';
    $order->save();
    Log::info('Order updated to "Kadaluarsa"', ['orderId' => $orderId]);
}elseif ($transactionStatus == 'pending') {
    $order->payment_status = 'Menunggu Pembayaran';
    $order->save();
    Log::info('Order updated to "Menunggu Pembayaran"', ['orderId' => $orderId]);
}

// Return a success response
return response()->json([
    'status' => 'success',
    'message' => 'Notification success'
]);

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


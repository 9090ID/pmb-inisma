<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CheckExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update orders that have expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
 
        // Ambil semua order yang sudah kadaluarsa
        $orders = Order::where('payment_status', 'Menunggu Pembayaran')
                       ->where('expires_at', '<', now())
                       ->get();

        foreach ($orders as $order) {
            // Ubah status order menjadi Kadaluarsa jika sudah melewati waktu
            $order->update(['payment_status' => 'Kadaluarsa']);
            // Log perubahan status
            dd('Order expiration check started');
            Log::info('Order expired: ' . $order->id);
        }
    }
}

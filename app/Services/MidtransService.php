<?php
namespace App\Services;

use Midtrans\Config;

class MidtransService
{
    public static function configure()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true; // Untuk sanitasi data otomatis
        Config::$is3ds = true;       // Aktifkan 3DS untuk kartu kredit
    }
}
// Compare this snippet from app/Http/Controllers/HomeController.php:
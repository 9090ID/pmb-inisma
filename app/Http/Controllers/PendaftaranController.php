<?php

namespace App\Http\Controllers;

use App\Mail\KonfirmasiPendaftaran;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Order;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;
use App\Mail\RegistrationMail;
use App\Models\JalurMasuk;

class PendaftaranController extends Controller
{
    public function create()
    {
        $programStudi = ProgramStudi::all();
        $jalurMasuk = JalurMasuk::all();
        return view('pendaftaran.index', compact('programStudi', 'jalurMasuk'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'namalengkap' => 'required|string|max:255|not_regex:/<[^>]+>/',
            'tanggallahir' => 'required|date|before:today|date_format:Y-m-d',
            'nomorhp' => 'required|string|max:15|regex:/^[0-9\+\s]+$/',
            'email' => 'required|email|unique:users,email',
            'alamat' => 'required|string|max:500|not_regex:/<[^>]+>/',
            'prodiyangdipilih' => 'required|string',
            'jalurmasuk_id' => 'required|exists:jalurmasuk,id',
        ], [
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'namalengkap.not_regex' => 'Nama lengkap tidak boleh mengandung tag HTML.',
            'alamat.not_regex' => 'Alamat tidak boleh mengandung tag HTML.',
            'jalurmasuk_id.exists' => 'Jalur masuk yang dipilih tidak valid.',
        ]);
    
        $jalurMasuk = JalurMasuk::find($request->jalurmasuk_id);
    
        // Validasi untuk Jalur Tahfizh
        if ($jalurMasuk && $jalurMasuk->nm_jalur === 'Jalur Tahfizh') {
            $validator->after(function ($validator) use ($request) {
                if (!$request->has('jumlah_juz') || is_null($request->jumlah_juz)) {
                    $validator->errors()->add('jumlah_juz', 'Jumlah Juz harus dipilih untuk jalur Tahfizh.');
                }
    
                if (!$request->hasFile('sertifikat')) {
                    $validator->errors()->add('sertifikat', 'Sertifikat harus diunggah untuk jalur Tahfizh.');
                }
    
                if ($request->hasFile('sertifikat') && $request->file('sertifikat')->isValid()) {
                    $fileSize = $request->file('sertifikat')->getSize();
                    if ($fileSize > 10 * 1024 * 1024) {
                        $validator->errors()->add('sertifikat', 'Ukuran sertifikat tidak boleh lebih dari 10MB.');
                    }
                }
            });
        }
    
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['message' => implode("\n", $errors)], 422);
        }
    
        $password = Str::random(8);
        $user = User::create([
            'name' => $request->namalengkap,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'mahasiswa',
        ]);
    
        $tanggalLahir = Carbon::parse($request->tanggallahir)->format('Ymd');
        $totalCount = Pendaftaran::count();
        $nomorRegistrasi = $tanggalLahir . '-' . str_pad($totalCount + 1, 3, '0', STR_PAD_LEFT);
    
        // Menyimpan data pendaftaran dengan sertifikat dan jumlah juz
        $sertifikatPath = null;
        if ($request->hasFile('sertifikat')) {
            // Menyimpan file sertifikat
            $sertifikatPath = $request->file('sertifikat')->store('sertifikat', 'public');
        }
    
        $pendaftaran = Pendaftaran::create([
            'namalengkap' => $request->namalengkap,
            'tanggallahir' => $request->tanggallahir,
            'nomorhp' => $request->nomorhp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'prodiyangdipilih' => $request->prodiyangdipilih,
            'user_id' => $user->id,
            'nomor_registrasi' => $nomorRegistrasi,
            'jalurmasuk_id' => $request->jalurmasuk_id,
            'jumlah_juz' => $jalurMasuk->nm_jalur === 'Jalur Tahfizh' ? $request->jumlah_juz : null,
            'sertifikat' => $jalurMasuk->nm_jalur === 'Jalur Tahfizh' ? $sertifikatPath : null, // Path sertifikat
        ]);
    
        // Buat Order dan Snap Token Midtrans
        $this->createOrder($user, $request, $pendaftaran);
    
        // Kirim email konfirmasi
        try {
            Mail::to($user->email)->send(new KonfirmasiPendaftaran($user->name, $request->email, $password, $nomorRegistrasi));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Gagal mengirim email. Silakan coba lagi nanti.'], 500);
        }
    
        return response()->json([
            'message' => 'Pendaftaran berhasil! Silakan cek email Anda untuk login dan lakukan pembayaran.',
            'nomor_registrasi' => $nomorRegistrasi,
        ], 200);
    }
    
    private function createOrder($user, $request, $pendaftaran)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-bfk8clRtIGPqE8xfhuf5jziX';
        Config::$isProduction = false;

        // Ambil jalur masuk berdasarkan ID yang dipilih
        $jalurMasuk = JalurMasuk::find($request->jalurmasuk_id);

        if (!$jalurMasuk) {
            throw new \Exception('Jalur masuk tidak ditemukan.');
        }

        // Periksa apakah jalur adalah Tahfiz
        if ($jalurMasuk->nm_jalur === 'Jalur Tahfizh') {
            // Jika jalur Tahfiz, buat order dengan status Gratis
            Order::create([
                'number' => uniqid(), // Nomor unik untuk order
                'total_price' => 0, // Gratis
                'payment_status' => 'Gratis', // Status pembayaran
                'snap_token' => '0', // Tidak memerlukan Snap token
                'user_id' => $user->id, // Hubungkan dengan user
                'expires_at' => null, // Tidak memiliki masa kadaluarsa
            ]);
        } else {
            // Jika bukan Tahfiz, buat order dengan snap token
            $expiryDurationInSeconds = 60; // 1 menit

            // Buat transaksi
            $transactionDetails = [
                'order_id' => uniqid(),
                'gross_amount' => $jalurMasuk->biaya_pendaftaran, // Ambil dari jalur masuk
                'expiry' => [
                    'start_time' => now()->toIso8601String(), // Waktu mulai transaksi
                    'duration' => $expiryDurationInSeconds, // Durasi transaksi dalam detik
                ]
            ];

            $itemDetails = [
                [
                    'id' => $pendaftaran->nomor_registrasi, // Menggunakan nomor registrasi sebagai ID
                    'price' => $jalurMasuk->biaya_pendaftaran, // Ambil dari jalur masuk
                    'quantity' => 1,
                    'name' => $jalurMasuk->nm_jalur, // Nama kelas yang dipilih
                ],
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->namalengkap,
                    'email' => $request->email,
                    'phone' => $request->nomorhp,
                    'billing_address' => [
                        'address' => $request->alamat,
                    ],
                    'shipping_details' => [
                        'address' => $pendaftaran->alamat, // Ambil dari pendaftaran
                    ],
                ],
            ];

            // Dapatkan token pembayaran
            try {
                $snapToken = Snap::getSnapToken($transactionData);
            } catch (\Exception $e) {
                // Tangani kesalahan jika terjadi
                return response()->json(['error' => $e->getMessage()], 400);
            }

            // Simpan data order
            Order::create([
                'number' => $transactionDetails['order_id'],
                'total_price' => $transactionDetails['gross_amount'],
                'payment_status' => 'Menunggu Pembayaran', // Status awal
                'snap_token' => $snapToken,
                'user_id' => $user->id, // Hubungkan dengan user
                'expires_at' => now()->addSeconds($expiryDurationInSeconds), // Waktu kadaluarsa
            ]);
        }
    }
}

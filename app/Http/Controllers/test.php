public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'namalengkap' => 'required|string|max:255',
            'tanggallahir' => 'required|date',
            'nomorhp' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email', // Validasi email unik
            'alamat' => 'required|string',
            'prodiyangdipilih' => 'required|string',
        ], [
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.' // Pesan kustom
        ]);
     
        // Generate random password
        $password = Str::random(8); // Password acak

        // Simpan data pengguna
        $user = User::create([
            'name' => $request->namalengkap,
            'email' => $request->email,
            'password' => Hash::make($password), // Hash password
            'role' => 'mahasiswa',
        ]);

        // Simpan data pendaftaran dengan user_id
        $pendaftaran = Pendaftaran::create([
            'namalengkap' => $request->namalengkap,
            'tanggallahir' => $request->tanggallahir,
            'nomorhp' => $request->nomorhp,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'prodiyangdipilih' => $request->prodiyangdipilih,
            'aktif' => 1, // Atur aktif sesuai kebutuhan
            'user_id' => $user->id, // Hubungkan dengan user
        ]);
 
        // Konfigurasi Midtrans
        Config::$serverKey = 'SB-Mid-server-bfk8clRtIGPqE8xfhuf5jziX'; // Ganti dengan server key Anda
        Config::$isProduction = false; // Ubah ke true jika sudah di produksi

        // Buat transaksi
        $transactionDetails = [
            'order_id' => uniqid(), // ID unik untuk transaksi
            'gross_amount' => 300000, // Jumlah pembayaran
        ];

        $itemDetails = [
            [
                'id' => $request->tanggalalhir,
                'price' => 300000,
                'quantity' => 1,
                'name' => 'Pembayaran Pendafatran Mahasiswa Baru Inisma',
            ],
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->namalengkap,
                'email' => $request->email,
            ],
        ];
         // Debugging: Log transaction data
    Log::info('Transaction Data:', $transactionData);

    // Dapatkan token pembayaran
    try {
        $snapToken = Snap::getSnapToken($transactionData);
    } catch (\Exception $e) {
        Log::error('Error getting Snap token: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'Gagal mendapatkan token pembayaran.']);
    }
    $order = Order::create([
        'number' => uniqid(),
        'total_price' => 100000,
        'payment_status' => '1',
        'snap_token' => $snapToken,
    ]);
        // Redirect ke halaman pembayaran Midtrans
        // return view('pendaftaran.pembayaran', compact('snapToken', 'user', 'password'));
        return response()->json(['snapToken' => $snapToken]);
    }





    
{{-- 
@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('registration-form').onsubmit = function(event) {
            event.preventDefault(); // Mencegah form dari submit default

            var formData = new FormData(this);
            var transactionData = {}; // Siapkan objek untuk menyimpan data transaksi

            // Kirim data pendaftaran ke server untuk mendapatkan snap token
            fetch('{{ route('pendaftaran.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snapToken) {
                        // Jika berhasil mendapatkan snap token, tampilkan tombol bayar
                        document.getElementById('payment-container').style.display = 'block';
                        document.getElementById('pay-button').onclick = function() {
                            snap.pay(data.snapToken, {
                                onSuccess: function(result) {
                                    console.log(result);
                                    alert('Pembayaran berhasil!');
                                },
                                onPending: function(result) {
                                    console.log(result);
                                    alert('Pembayaran Anda sedang diproses.');
                                },
                                onError: function(result) {
                                    console.log(result);
                                    alert('Terjadi kesalahan saat melakukan pembayaran.');
                                }
                            });
                        };
                    } else {
                        alert('Gagal mendapatkan token pembayaran.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghubungi server.');
                });
        };
    </script> --}}
@endpush
{{-- <div id="payment-container" style="display:none;">
                            <h3>Silahkan Anda Membayar....</h3>
                            <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
                        </div> --}}
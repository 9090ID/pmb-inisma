<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\JalurMasukController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\IdentitasDetailController;
use App\Models\JalurMasuk;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landingpage.index');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

//Halaman Utama
Route::resource('/landingpage', LandingpageController::class);
Route::get('/daftar-pmb', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
Route::post('/daftar-pmb', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
Route::post('/notification/handling', [PaymentCallbackController::class,'notification']);

Route::group(['middleware' => ['auth', 'role:masteradmin']], function () {
    Route::get('/masteradmin', [HomeController::class, 'masterAdmin']);
    Route::resource('rekap', RekapController::class);
    Route::get('/pendaftar', [RekapController::class, 'getPendaftar'])->name('pendaftar.data');
    Route::resource('jalurmasuk', JalurMasukController::class);
    Route::get('tampilkan/data', [JalurMasukController::class, 'getData'])->name('tampilkan.data');
    Route::resource('fakultas', FakultasController::class);
    Route::get('ambildata/data', [FakultasController::class, 'getData'])->name('ambildata.data');
    Route::resource('programstudi', ProdiController::class);
    Route::get('prodi/data', [ProdiController::class, 'getData'])->name('prodi.data');
    // Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
   
    
    
    

});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin', [HomeController::class, 'admin']);
});

Route::group(['middleware' => ['auth', 'role:mahasiswa']], function () {
    Route::get('/pmb-mahasiswa', [HomeController::class, 'mahasiswa']);
    Route::get('/payment/receipt/{order_number}', [PaymentCallbackController::class, 'downloadReceipt'])->name('payment.receipt');
    Route::post('/regenerate-payment', [HomeController::class, 'regeneratePayment'])->name('regenerate_payment');
    //Identitas Mahasiswa
    Route::get('/identitas', [IdentitasDetailController::class, 'index']);
    Route::post('/identitas', [IdentitasDetailController::class, 'store']);
    Route::post('/identitas/{identitas}', [IdentitasDetailController::class, 'update']);

  



});
require __DIR__.'/auth.php';

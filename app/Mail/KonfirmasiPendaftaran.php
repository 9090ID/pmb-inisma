<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class KonfirmasiPendaftaran extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;
    public $nomor_registrasi; // Tambahkan properti ini

    public function __construct($name, $email, $password, $nomor_registrasi) // Tambahkan parameter ini
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->nomor_registrasi = $nomor_registrasi; // Simpan nomor registrasi
    }

    public function build()
    {
        return $this->subject('Informasi Akun Anda')
                    ->view('pendaftaran.konfirmasi'); // Pastikan Anda membuat view ini
    }
}
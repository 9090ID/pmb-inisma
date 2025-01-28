<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;
    protected $table = 'pendaftarans';
    protected $fillable = [
        'namalengkap',
        'tanggallahir',
        'nomorhp',
        'email',
        'alamat',
        'prodiyangdipilih',
        'nomor_registrasi',
        'sertifikat', // Kolom baru
        'jumlah_juz', // Kolom baru
        'jalurmasuk_id', // Menambahkan jalurmasuk_id ke fillable
        'user_id', // Tambahkan user_id ke fillable
    ];
    public function setNameAttribute($value)
    {
        $this->attributes['namalengkap'] = strip_tags($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = filter_var($value, FILTER_SANITIZE_EMAIL);
    }
    public function setAlamatAttribute($value)
    {
        // Menghapus tag HTML dan menghapus spasi berlebih
        $this->attributes['alamat'] = trim(strip_tags($value));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id'); // Menghubungkan berdasarkan user_id
    }
    public function jalurMasuk()
    {
        return $this->belongsTo(JalurMasuk::class, 'jalurmasuk_id');
    }
}

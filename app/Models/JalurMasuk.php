<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JalurMasuk extends Model
{
    use HasFactory;
    protected $table = 'jalurmasuk';
    protected $fillable = [
        'nm_jalur',
        'tahun',
        'biaya_pendaftaran',
        'mulai_pendaftaran',
        'selesai_pendaftaran',
    ];

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'jalurmasuk_id');
    }
}

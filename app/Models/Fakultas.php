<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika berbeda dari konvensi
    protected $table = 'fakultas';

    // Tentukan field yang dapat diisi massal
    protected $fillable = [
        'nm_fakultas',
        'kode_fakultas',
        'akreditasi',
    ];

    // Jika Anda menggunakan timestamps
    public $timestamps = true;

    public function programStudi()
{
    return $this->hasMany(ProgramStudi::class);
}
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'total_price',
        'payment_status',
        'snap_token',
        'user_id',
        'updated_at',
        'expires_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class, 'user_id', 'user_id');
    }
}

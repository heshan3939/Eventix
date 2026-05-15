<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id', 'amount', 'platform_fee', 'payment_method', 'payment_reference', 'status'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2'
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

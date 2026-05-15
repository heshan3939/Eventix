<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'event_id', 'name', 'price', 'quantity', 'max_per_booking', 'sale_ends_at'
    ];

    protected function casts(): array
    {
        return [
            'sale_ends_at' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

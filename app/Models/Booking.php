<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'ticket_type_id', 'quantity', 'total_price', 'reference', 'status', 'cancelled_at',
        'stripe_session_id', 'payment_status'
    ];

    protected function casts(): array
    {
        return [
            'cancelled_at' => 'datetime',
            'total_price' => 'decimal:2'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    // Convenient accessor to the Event via TicketType
    public function event()
    {
        return $this->ticketType->event;
    }

    // Helper to check payment status
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function cancel(): void
    {
        if ($this->status !== 'cancelled') {
            $this->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            if ($this->transaction) {
                $this->transaction->update(['status' => 'refunded']);
            }
        }
    }
}

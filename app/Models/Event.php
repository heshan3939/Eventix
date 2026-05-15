<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organiser_id', 'title', 'description', 'venue', 'city', 'latitude', 'longitude',
        'category', 'starts_at', 'ends_at', 'total_capacity', 
        'banner_image', 'status', 'rejection_reason'
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function organiser()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class);
    }

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, TicketType::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'published')
                     ->where('starts_at', '>', now())
                     ->orderBy('starts_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('city', 'LIKE', "%{$term}%")
              ->orWhere('venue', 'LIKE', "%{$term}%");
        });
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    protected function bookedCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->bookings()->where('bookings.status', 'confirmed')->sum('bookings.quantity'),
        );
    }

    protected function availableSeats(): Attribute
    {
        return Attribute::make(
            get: fn () => max(0, $this->total_capacity - $this->booked_count),
        );
    }

    protected function isSoldOut(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->available_seats <= 0,
        );
    }

    protected function totalRevenue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->bookings()->where('bookings.status', 'confirmed')->sum('bookings.total_price'),
        );
    }

    protected function platformRevenue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->bookings()
                ->where('bookings.status', 'confirmed')
                ->join('transactions', 'bookings.id', '=', 'transactions.booking_id')
                ->where('transactions.status', 'paid')
                ->sum('transactions.platform_fee'),
        );
    }
}

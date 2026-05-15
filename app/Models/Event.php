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
            'latitude' => 'float',
            'longitude' => 'float',
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

    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->banner_image) {
                    return asset('storage/' . $this->banner_image);
                }

                $defaults = [
                    'music'      => 'https://images.unsplash.com/photo-1514525253361-bee8718a300a?q=80&w=1000&auto=format&fit=crop',
                    'conference' => 'https://images.unsplash.com/photo-1540575861501-7ad05823c93f?q=80&w=1000&auto=format&fit=crop',
                    'culture'    => 'https://images.unsplash.com/photo-1467307983825-619715426c70?q=80&w=1000&auto=format&fit=crop',
                    'sports'     => 'https://images.unsplash.com/photo-1504450758481-7338eba7524a?q=80&w=1000&auto=format&fit=crop',
                    'education'  => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=1000&auto=format&fit=crop',
                ];

                return $defaults[$this->category] ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1000&auto=format&fit=crop';
            },
        );
    }
}

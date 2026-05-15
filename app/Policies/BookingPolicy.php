<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $booking->user_id === $user->id;
    }
}

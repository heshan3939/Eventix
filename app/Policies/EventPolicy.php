<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function before(User $user): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function update(User $user, Event $event): bool
    {
        return $event->organiser_id === $user->id;
    }

    public function delete(User $user, Event $event): bool
    {
        return $event->organiser_id === $user->id;
    }
}

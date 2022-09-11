<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    public function getNotificationsByFilters(array $array): Collection
    {
        return Notification::query()
            ->where('user_id', $array['user']->id)
            ->limit($array['limit'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function markReadNotifications(User|Model $user): void
    {
        Notification::query()
            ->where('user_id', $user->id)
            ->update(['read_at' => now()]);
    }
}

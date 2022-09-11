<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;

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
}

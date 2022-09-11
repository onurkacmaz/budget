<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(public NotificationService $notificationService){}

    public function index(Request $request): JsonResponse {
        $user = $request->user();
        $limit = $request->get('limit');

        $notifications = $this->notificationService->getNotificationsByFilters([
            'user' => $user,
            'limit' => $limit
        ]);

        return response()->json($notifications);
    }

    public function markRead(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->notificationService->markReadNotifications($user);

        return response()->json(['message' => 'Notifications marked as read']);
    }
}

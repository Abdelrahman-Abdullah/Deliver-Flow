<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        // Fetch all notifications for the authenticated user
        $notifications = $request->user()->notifications()->latest()->paginate(20);

        return $this->successResponse($notifications, 'Notifications retrieved successfully.');

    }   

    public function unread(Request $request)
    {
        // Fetch only unread notifications
        $unreadNotifications = $request->user()->unreadNotifications()->latest()->get();

        return $this->successResponse([
            'count' => $unreadNotifications->count(),
            'notifications' => $unreadNotifications
        ], 'Unread notifications retrieved successfully.');
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->errorResponse('Notification not found.', 404);
        }

        $notification->markAsRead();

        return $this->successResponse(message: 'Notification marked as read.');
    }

}

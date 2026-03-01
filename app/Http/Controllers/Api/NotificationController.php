<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    private const DEFAULT_PER_PAGE = 20;
    private const MAX_PER_PAGE = 50;

    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate($this->resolvePerPage($request));

        $notifications->getCollection()->transform(
            fn ($notification) => $this->formatNotification($notification)
        );

        return $this->successResponse($notifications, 'Notifications retrieved successfully.');

    }   

    public function unread(Request $request)
    {
        $unreadQuery = $request->user()->unreadNotifications()->latest();
        $unreadCount = (clone $unreadQuery)->count();

        $unreadNotifications = $unreadQuery->paginate($this->resolvePerPage($request));
        $unreadNotifications->getCollection()->transform(
            fn ($notification) => $this->formatNotification($notification)
        );

        return $this->successResponse([
            'count' => $unreadCount,
            'notifications' => $unreadNotifications
        ], 'Unread notifications retrieved successfully.');
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = $this->findUserNotification($request, $id);

        if (!$notification) {
            return $this->notFoundResponse('Notification not found.');
        }

        $notification->markAsRead();

        return $this->successResponse(message: 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $updated = $request->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return $this->successResponse(
            data: ['updated' => $updated],
            message: 'All notifications marked as read.'
        );
    }

    public function destroy(Request $request, string $id)
    {
        $notification = $this->findUserNotification($request, $id);

        if (!$notification) {
            return $this->notFoundResponse('Notification not found.');
        }

        $notification->delete();

        return $this->successResponse(message: 'Notification deleted successfully.');
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->integer('per_page', self::DEFAULT_PER_PAGE);

        return max(1, min(self::MAX_PER_PAGE, $perPage));
    }

    private function findUserNotification(Request $request, string $id): ?object
    {
        return $request->user()->notifications()->where('id', $id)->first();
    }

    private function formatNotification( $notification): array
    {
        return [
            'id' => $notification->id,
            'title' => data_get($notification->data, 'title'),
            'body' => data_get($notification->data, 'body'),
            'type' => data_get($notification->data, 'type', class_basename($notification->type)),
            'data' => data_get($notification->data, 'data', []),
            'sent_at' => data_get($notification->data, 'sent_at', $notification->created_at),
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at,
        ];
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->data['type'] ?? 'general',
                        'title' => $notification->data['title'] ?? 'Notifikasi',
                        'message' => $notification->data['message'] ?? '',
                        'action_type' => $notification->data['action_type'] ?? null,
                        'action_id' => $notification->data['action_id'] ?? null,
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                        'time_ago' => $notification->created_at->diffForHumans(),
                    ];
                }),
                'unread_count' => $user->unreadNotifications()->count(),
                'meta' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'total' => $notifications->total(),
                ]
            ]
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai sudah dibaca.'
        ]);
    }

    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai belum dibaca.'
        ]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai sudah dibaca.'
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'unread_count' => Auth::user()->unreadNotifications()->count()
            ]
        ]);
    }
}

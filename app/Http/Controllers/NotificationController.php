<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()
            ->user()
            ->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function readAll()
    {
        auth()
            ->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function read($notification)
    {
        $notification = auth()
            ->user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }
}
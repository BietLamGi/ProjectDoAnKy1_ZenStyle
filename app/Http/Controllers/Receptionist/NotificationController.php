<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Notification;

/**
 * Receptionist only receives/reads system notifications here. Sending or
 * broadcasting notifications is a system/admin capability and is
 * intentionally not exposed on this controller.
 */
class NotificationController extends Controller
{
    /**
     * Internal notification list.
     */
    public function index()
    {
        $notifications = Notification::with('user')
            ->orderBy('NotificationID', 'desc')
            ->paginate(12);

        return view('receptionist.notifications.index', compact('notifications'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Notification $notification)
    {
        $notification->update(['IsRead' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Notification::where('IsRead', false)->update(['IsRead' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}

<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderByDesc('id')->paginate(12);

        return view('receptionist.notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Đã đánh dấu thông báo là đã đọc.');
    }

    public function markAllRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
}

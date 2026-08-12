<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Danh sách thông báo nội bộ.
     */
    public function index()
    {
        $notifications = Notification::with('user')
            ->orderBy('NotificationID', 'desc')
            ->paginate(12);

        return view('receptionist.notifications.index', compact('notifications'));
    }

    /**
     * Form tạo thông báo mới (gửi cho 1 nhân viên hoặc toàn bộ hệ thống).
     */
    public function create()
    {
        $users = User::orderBy('Username')->get();

        return view('receptionist.notifications.create', compact('users'));
    }

    /**
     * Lưu thông báo mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'UserID' => 'nullable|exists:User,UserID',
            'Title' => 'required|string|max:150',
            'Message' => 'required|string|max:500',
            'Type' => 'nullable|string|max:30',
        ]);

        $validated['IsRead'] = false;
        $validated['CreatedAt'] = now();

        Notification::create($validated);

        return redirect()
            ->route('receptionist.notifications.index')
            ->with('success', 'Đã gửi thông báo.');
    }

    /**
     * Đánh dấu 1 thông báo là đã đọc.
     */
    public function markRead(Notification $notification)
    {
        $notification->update(['IsRead' => true]);

        return back()->with('success', 'Đã đánh dấu thông báo là đã đọc.');
    }

    /**
     * Đánh dấu tất cả thông báo là đã đọc.
     */
    public function markAllRead()
    {
        Notification::where('IsRead', false)->update(['IsRead' => true]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
}

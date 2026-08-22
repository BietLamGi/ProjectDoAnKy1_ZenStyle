<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->orderBy('NotificationID', 'desc')
            ->paginate(10);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::orderBy('Username')->get();

        return view('admin.notifications.create', compact('users'));
    }

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
            ->route('notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $users = User::orderBy('Username')->get();

        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        $validated = $request->validate([
            'UserID' => 'nullable|exists:User,UserID',
            'Title' => 'required|string|max:150',
            'Message' => 'required|string|max:500',
            'Type' => 'nullable|string|max:30',
            'IsRead' => 'required|boolean',
        ]);

        $notification->update($validated);

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notification updated successfully.');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }
}
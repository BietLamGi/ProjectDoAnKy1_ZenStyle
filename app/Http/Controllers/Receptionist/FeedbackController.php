<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $feedbacks = Feedback::with(['customer', 'appointment'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.feedbacks.index', compact('feedbacks', 'status'));
    }

    /**
     * Đánh dấu phản hồi đã xử lý.
     */
    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:new,reviewed,resolved',
        ]);

        $feedback->update(['status' => $request->status]);

        return back()->with('success', 'Đã cập nhật trạng thái phản hồi.');
    }
}

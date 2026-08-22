<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Customer feedback list - filter by rating and search by customer,
     * so the receptionist can spot low ratings and follow up on complaints.
     */
    public function index(Request $request)
    {
        $rating = $request->query('rating');
        $keyword = $request->query('q');

        $feedbacks = Feedback::with(['appointment.customer'])
            ->when($rating, fn ($query) => $query->where('Rating', $rating))
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('appointment.customer', function ($q) use ($keyword) {
                    $q->where('FullName', 'like', "%{$keyword}%")
                        ->orWhere('Phone', 'like', "%{$keyword}%");
                });
            })
            ->orderByDesc('FeedbackDate')
            ->orderByDesc('FeedbackID')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.feedbacks.index', compact('feedbacks', 'rating', 'keyword'));
    }
}

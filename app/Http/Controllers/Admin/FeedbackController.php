<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Appointment;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('appointment')
            ->orderBy('FeedbackID', 'desc')
            ->paginate(10);

        return view('admin.feedbacks.index', compact('feedbacks'));
    }

    public function create()
    {
        $appointments = Appointment::orderBy('AppointmentDate', 'desc')->get();

        return view('admin.feedbacks.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'AppointmentID' => 'required|exists:Appointment,AppointmentID',
            'Rating' => 'required|integer|min:1|max:5',
            'Comments' => 'nullable|string|max:500',
        ]);

        $validated['FeedbackDate'] = now();

        Feedback::create($validated);

        return redirect()
            ->route('feedbacks.index')
            ->with('success', 'Feedback created successfully!');
    }

    public function edit(Feedback $feedback)
    {
        $appointments = Appointment::orderBy('AppointmentDate', 'desc')->get();

        return view('admin.feedbacks.edit', compact(
            'feedback',
            'appointments'
        ));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'AppointmentID' => 'required|exists:Appointment,AppointmentID',
            'Rating' => 'required|integer|min:1|max:5',
            'Comments' => 'nullable|string|max:500',
            'FeedbackDate' => 'required|date',
        ]);

        $feedback->update($validated);

        return redirect()
            ->route('feedbacks.index')
            ->with('success', 'Feedback updated successfully!');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()
            ->route('feedbacks.index')
            ->with('success', 'Feedback deleted successfully!');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('service')
            ->orderBy('PromotionID', 'desc')
            ->paginate(10);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $services = Service::where('IsActive', 1)
            ->orderBy('ServiceName')
            ->get();

        return view('admin.promotions.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Title' => 'required|string|max:100',
            'ServiceID' => 'nullable|exists:Service,ServiceID',
            'Description' => 'nullable|string|max:500',
            'DiscountType' => 'required|string|max:20',
            'DiscountValue' => 'required|numeric|min:0',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
        ]);

        $validated['IsActive'] = $request->has('IsActive') ? 1 : 0;

        Promotion::create($validated);

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(Promotion $promotion)
    {
        $services = Service::where('IsActive', 1)
            ->orderBy('ServiceName')
            ->get();

        return view('admin.promotions.edit', compact(
            'promotion',
            'services'
        ));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'Title' => 'required|string|max:100',
            'ServiceID' => 'nullable|exists:Service,ServiceID',
            'Description' => 'nullable|string|max:500',
            'DiscountType' => 'required|string|max:20',
            'DiscountValue' => 'required|numeric|min:0',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
        ]);

        $validated['IsActive'] = $request->has('IsActive') ? 1 : 0;

        $promotion->update($validated);

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()
            ->route('promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }
}
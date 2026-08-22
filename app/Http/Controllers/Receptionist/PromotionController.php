<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Promotion;
use Illuminate\Http\Request;

/**
 * Read-only for the receptionist: lookup promotions to apply when billing.
 * Creating/editing/deleting promotion campaigns is an Admin/Marketing task
 * and is intentionally not exposed here.
 */
class PromotionController extends Controller
{
    /**
     * Promotion list (lookup only).
     */
    public function index(Request $request)
    {
        $keyword = $request->query('q');

        $promotions = Promotion::with('service')
            ->when($keyword, fn ($query) => $query->where('Title', 'like', "%{$keyword}%"))
            ->orderBy('PromotionID', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('receptionist.promotions.index', compact('promotions', 'keyword'));
    }

    /**
     * View a single promotion's details (lookup only).
     */
    public function show(Promotion $promotion)
    {
        $promotion->load('service');

        return view('receptionist.promotions.show', compact('promotion'));
    }
}

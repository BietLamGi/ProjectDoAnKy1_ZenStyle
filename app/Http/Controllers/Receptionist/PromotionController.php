<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Service;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Danh sách khuyến mãi.
     */
    public function index()
    {
        $promotions = Promotion::with('service')
            ->orderBy('PromotionID', 'desc')
            ->paginate(10);

        return view('receptionist.promotions.index', compact('promotions'));
    }

    /**
     * Form tạo khuyến mãi mới.
     */
    public function create()
    {
        $services = Service::where('IsActive', 1)
            ->orderBy('ServiceName')
            ->get();

        return view('receptionist.promotions.create', compact('services'));
    }

    /**
     * Lưu khuyến mãi mới.
     */
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
            ->route('receptionist.promotions.index')
            ->with('success', 'Đã tạo chương trình khuyến mãi.');
    }

    /**
     * Form sửa khuyến mãi.
     */
    public function edit(Promotion $promotion)
    {
        $services = Service::where('IsActive', 1)
            ->orderBy('ServiceName')
            ->get();

        return view('receptionist.promotions.edit', compact(
            'promotion',
            'services'
        ));
    }

    /**
     * Cập nhật khuyến mãi.
     */
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
            ->route('receptionist.promotions.index')
            ->with('success', 'Đã cập nhật chương trình khuyến mãi.');
    }

    /**
     * Xoá khuyến mãi.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()
            ->route('receptionist.promotions.index')
            ->with('success', 'Đã xoá chương trình khuyến mãi.');
    }
}

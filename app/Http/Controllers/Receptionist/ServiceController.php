<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Bảng giá dịch vụ & sản phẩm để lễ tân tra cứu khi tư vấn/checkout.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', '0'); // 0 = dịch vụ, 1 = sản phẩm
        $keyword = $request->query('q');

        $services = Service::query()
            ->where('ServiceType', $type)
            ->when($keyword, fn ($query) => $query->where('ServiceName', 'like', "%{$keyword}%"))
            ->orderBy('Category')
            ->orderBy('ServiceName')
            ->get()
            ->groupBy('Category');

        return view('receptionist.services.index', compact('services', 'type', 'keyword'));
    }
}

<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Service & product price list for the receptionist to look up when consulting customers or checking out.
     */
    public function index(Request $request)
    {
        $type = $request->query('type', '0'); // 0 = service, 1 = product
        $keyword = $request->query('q');

        $services = Service::with('activePromotion')
            ->where('ServiceType', $type)
            ->when($keyword, fn ($query) => $query->where('ServiceName', 'like', "%{$keyword}%"))
            ->orderBy('Category')
            ->orderBy('ServiceName')
            ->get()
            ->groupBy('Category');

        return view('receptionist.services.index', compact('services', 'type', 'keyword'));
    }
}
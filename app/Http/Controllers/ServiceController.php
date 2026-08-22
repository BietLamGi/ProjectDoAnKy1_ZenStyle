<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of services and products.
     */
    public function index()
    {
        // Services

        
        $hairServices = Service::with('activePromotion')
            ->where('ServiceType', 0)
            ->where('Category', 'Hair')
            ->where('IsActive', 1)
            ->get();

        $skinServices = Service::with('activePromotion')
            ->where('ServiceType', 0)
            ->where('Category', 'Skin')
            ->where('IsActive', 1)
            ->get();

        $massageServices = Service::with('activePromotion')
            ->where('ServiceType', 0)
            ->where('Category', 'Massage')
            ->where('IsActive', 1)
            ->get();

        // Products
        $hairProducts = Service::with('activePromotion')
            ->where('ServiceType', 1)
            ->where('Category', 'Hair')
            ->where('IsActive', 1)
            ->get();

        $skinProducts = Service::with('activePromotion')
            ->where('ServiceType', 1)
            ->where('Category', 'Skin')
            ->where('IsActive', 1)
            ->get();

        $massageProducts = Service::with('activePromotion')
            ->where('ServiceType', 1)
            ->where('Category', 'Massage')
            ->where('IsActive', 1)
            ->get();

        return view('customer.service.index', compact(
            'hairServices',
            'skinServices',
            'massageServices',
            'hairProducts',
            'skinProducts',
            'massageProducts'
        ));
    }
}
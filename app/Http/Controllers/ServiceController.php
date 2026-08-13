<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    $hairServices = Service::where('ServiceType',0)
        ->where('Category','Hair')
        ->get();

    $skinServices = Service::where('ServiceType',0)
        ->where('Category','Skin')
        ->get();

    $massageServices = Service::where('ServiceType',0)
        ->where('Category','Massage')
        ->get();

    // Products
    $hairProducts = Service::where('ServiceType',1)
        ->where('Category','Hair')
        ->get();

    $skinProducts = Service::where('ServiceType',1)
        ->where('Category','Skin')
        ->get();

    $massageProducts = Service::where('ServiceType',1)
        ->where('Category','Massage')
        ->get();

    return view('service.index', compact(
        'hairServices',
        'skinServices',
        'massageServices',
        'hairProducts',
        'skinProducts',
        'massageProducts'
    ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}

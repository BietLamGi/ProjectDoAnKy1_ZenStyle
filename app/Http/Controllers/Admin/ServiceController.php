<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller{

    public function index()
    {
        $services = Service::orderBy('ServiceID', 'desc')
            ->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ServiceType' => 'required|boolean',
            'ServiceName' => 'required|string|max:100',
            'Description' => 'nullable|string|max:500',
            'DurationMinutes' => 'required|integer|min:1',
            'Price' => 'required|numeric|min:0',
            'IsActive' => 'nullable|boolean',
        ]);

        $validated['IsActive'] = $request->has('IsActive');

        Service::create($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'ServiceType' => 'required|boolean',
            'ServiceName' => 'required|string|max:100',
            'Description' => 'nullable|string|max:500',
            'DurationMinutes' => 'required|integer|min:1',
            'Price' => 'required|numeric|min:0',
            'IsActive' => 'nullable|boolean',
        ]);

        $validated['IsActive'] = $request->has('IsActive');

        $service->update($validated);

        return redirect()
            ->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
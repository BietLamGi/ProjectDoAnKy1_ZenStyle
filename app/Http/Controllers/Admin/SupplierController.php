<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::orderBy('SupplierID', 'desc')
            ->paginate(10);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'SupplierName' => 'required|string|max:100',
            'Phone' => 'nullable|string|max:20',
            'Email' => 'nullable|email|max:100',
            'Address' => 'nullable|string|max:255',
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Thêm nhà cung cấp thành công!');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'SupplierName' => 'required|string|max:100',
            'Phone' => 'nullable|string|max:20',
            'Email' => 'nullable|email|max:100',
            'Address' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Cập nhật nhà cung cấp thành công!');
    }
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Xóa nhà cung cấp thành công!');
    }
}
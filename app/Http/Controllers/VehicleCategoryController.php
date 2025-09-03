<?php

namespace App\Http\Controllers;

use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        $categories = VehicleCategory::orderBy('name')->get();
        return view('vehicle-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:vehicle_categories,slug',
            'is_active' => 'required|boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        VehicleCategory::create($data);

        return redirect()->route('vehicle-categories.index')->with('success', 'Category added successfully.');
    }

    public function edit(VehicleCategory $vehicleCategory)
    {
        return view('vehicle-categories.edit', compact('vehicleCategory'));
    }

    public function update(Request $request, VehicleCategory $vehicleCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:vehicle_categories,slug,' . $vehicleCategory->id,
            'is_active' => 'required|boolean',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $vehicleCategory->update($data);

        return redirect()->route('vehicle-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(VehicleCategory $vehicleCategory)
    {
        $vehicleCategory->delete();
        return redirect()->route('vehicle-categories.index')->with('success', 'Category deleted.');
    }

    public function ajaxCategories()
    {
        $categories = \App\Models\VehicleCategory::where('is_active', true)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']); // sirf zaruri fields

        return response()->json($categories);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('id','desc')->get(); // DataTables client-side
        return view('locations.index', compact('locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string|max:255|unique:locations,slug',
            'type' => 'required|in:city,town,village',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success','Location created successfully!');
    }

    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string|max:255|unique:locations,slug,' . $location->id,
            'type' => 'required|in:city,town,village',
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success','Location updated successfully.');
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Location deleted successfully.']);
        }

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }

    public function ajaxTowns()
    {
        $towns = \App\Models\Location::where('status', 'active')
            ->where('type', 'town') // sirf towns
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($towns);
    }




}

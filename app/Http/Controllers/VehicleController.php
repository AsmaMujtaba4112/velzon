<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['category','town'])->latest()->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'category_id'=> 'required|exists:vehicle_categories,id',
            'town_id'    => 'required|exists:locations,id',
            'status'     => 'required|in:Active,Inactive',
        ]);

        Vehicle::create($request->all());

        return redirect()->back()->with('success', 'Vehicle created successfully!');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::with(['category','town'])->findOrFail($id);
            return response()->json($vehicle);

    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'category_id'=> 'required|exists:vehicle_categories,id',
            'town_id'    => 'required|exists:locations,id',
            'status'     => 'required|in:Active,Inactive',
        ]);

        $vehicle->update($request->all());

        return redirect()->back()->with('success', 'Vehicle updated successfully!');
    }

    public function destroy($id)
    {
        Vehicle::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Vehicle deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\RentalCompany;

class VehicleController extends Controller
{
    public function create()
    {
        return view('vehicles.create');
    }


    public function index(Request $request)
    {
        $query = Vehicle::where('available', true);

        if ($request->brand) {
            $query->where('brand', 'ILIKE', '%' . $request->brand . '%');
        }

        if ($request->transmission) {
            $query->where('transmission', $request->transmission);
        }

        if ($request->fuel_type) {
            $query->where('fuel_type', $request->fuel_type);
        }

        if ($request->seats) {
            $query->where('seats', '>=', $request->seats);
        }

        if ($request->max_price) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $vehicles = $query->latest()->get();

        return view('vehicles.index', compact('vehicles'));
    }
    public function edit(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $request->validate([
            'title' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'price_per_day' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);


        $imagePath = $vehicle->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('vehicles', 'public');
        }

        $vehicle->update([
            'title' => $request->title,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'transmission' => $request->transmission,
            'fuel_type' => $request->fuel_type,
            'seats' => $request->seats,
            'price_per_day' => $request->price_per_day,
            'description' => $request->description,
            'available' => $request->has('available'),
            'image' => $imagePath,
        ]);

        return redirect('/company/vehicles')
            ->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $vehicle->delete();

        return back()->with('success', 'Vehicle deleted.');
    }

    private function authorizeVehicle(Vehicle $vehicle): void
    {
        $company = \App\Models\RentalCompany::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->firstOrFail();

        if ($vehicle->rental_company_id !== $company->id) {
            abort(403);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'price_per_day' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $company = RentalCompany::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->first();

        if (!$company) {
            return back()->with('error', 'Company not approved.');
        }

        $imagePath = null;

        if ($request->hasFile('image')) {

            $imagePath = $request->file('image')
                ->store('vehicles', 'public');

        }
        
        Vehicle::create([
            'rental_company_id' => $company->id,
            'title' => $request->title,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'transmission' => $request->transmission,
            'fuel_type' => $request->fuel_type,
            'seats' => $request->seats,
            'price_per_day' => $request->price_per_day,
            'description' => $request->description,
            'available' => true,
            'image' => $imagePath,
        ]);

        return redirect('/dashboard')
            ->with('success', 'Vehicle added successfully.');
    }
    public function myVehicles()
    {
        $company = \App\Models\RentalCompany::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->firstOrFail();

        $vehicles = Vehicle::where('rental_company_id', $company->id)
            ->latest()
            ->get();

        return view('vehicles.my', compact('vehicles'));
    }



}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\RentalCompany;
use App\Models\VehicleImage;
use Illuminate\Support\Facades\Storage;


class VehicleController extends Controller {
    public function create() {
        return view( 'vehicles.create' );
    }

    public function index( Request $request ) {
        $query = Vehicle::with(['reviews.customer','images'])
        ->where( 'available', true );

        if ( $request->brand ) {
            $query->where( 'brand', 'ILIKE', '%' . $request->brand . '%' );
        }

        if ( $request->transmission ) {
            $query->where( 'transmission', $request->transmission );
        }

        if ( $request->fuel_type ) {
            $query->where( 'fuel_type', $request->fuel_type );
        }

        if ( $request->seats ) {
            $query->where( 'seats', '>=', $request->seats );
        }

        if ( $request->max_price ) {
            $query->where( 'price_per_day', '<=', $request->max_price );
        }

        $vehicles = $query->latest()->get();

        return view( 'vehicles.index', compact( 'vehicles' ) );
    }

    public function edit( Vehicle $vehicle ) {
        $this->authorizeVehicle( $vehicle );

        return view( 'vehicles.edit', compact( 'vehicle' ) );
    }

    public function update( Request $request, Vehicle $vehicle ) {
        $this->authorizeVehicle( $vehicle );

        $request->validate( [
            'title' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'price_per_day' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:4096',
        ] );

        $imagePath = $vehicle->image;

        if ( $request->hasFile( 'image' ) ) {
            $imagePath = $request->file( 'image' )->store( 'vehicles', 'public' );
        }

        if ($request->hasFile('gallery_images')) {

            $existingCount = $vehicle->images()->count();

            foreach ($request->file('gallery_images') as $index => $image) {

                $path = $image->store('vehicles/gallery', 'public');

                $vehicle->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $existingCount + $index,
                ]);
            }
        }


        $vehicle->update( [
            'title' => $request->title,
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'transmission' => $request->transmission,
            'fuel_type' => $request->fuel_type,
            'seats' => $request->seats,
            'price_per_day' => $request->price_per_day,
            'description' => $request->description,
            'available' => $request->has( 'available' ),
            'image' => $imagePath,
        ] );

        return redirect( '/company/vehicles' )
        ->with( 'success', 'Vehicle updated.' );
    }

    public function destroy( Vehicle $vehicle ) {
        $this->authorizeVehicle( $vehicle );

        $vehicle->delete();

        return back()->with( 'success', 'Vehicle deleted.' );
    }



    public function deleteImage(VehicleImage $image)
    {
        $vehicle = $image->vehicle;

        $this->authorizeVehicle($vehicle);

        Storage::disk('public')->delete($image->image_path);

        $image->delete();

        return back()->with('success', 'Image deleted.');
    }

    public function setPrimaryImage(VehicleImage $image)
    {
        $vehicle = $image->vehicle;

        $this->authorizeVehicle($vehicle);

        $vehicle->images()->update([
            'is_primary' => false
        ]);

        $image->update([
            'is_primary' => true
        ]);

        return back()->with('success', 'Primary image updated.');
    }

    private function authorizeVehicle( Vehicle $vehicle ): void {
        $company = \App\Models\RentalCompany::where( 'user_id', auth()->id() )
        ->where( 'status', 'approved' )
        ->firstOrFail();

        if ( $vehicle->rental_company_id !== $company->id ) {
            abort( 403 );
        }
    }

    public function show( Vehicle $vehicle ) {
         $vehicle->load([
        'reviews.customer',
         'images'
        ]);

        return view( 'vehicles.show', compact( 'vehicle' ) );
    }

    public function store( Request $request ) {
        $request->validate( [
            'title' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'price_per_day' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:4096',
        ] );

        $company = RentalCompany::where( 'user_id', auth()->id() )
        ->where( 'status', 'approved' )
        ->first();

        if ( !$company ) {
            return back()->with( 'error', 'Company not approved.' );
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('vehicles', 'public');
        }

        $vehicle = Vehicle::create([
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

        if ($request->hasFile('gallery_images')) {

            foreach ($request->file('gallery_images') as $index => $image) {

                $path = $image->store('vehicles/gallery', 'public');

                $vehicle->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect('/company/vehicles')
            ->with('success', 'Vehicle added successfully.');
    }

    public function myVehicles() {
        $company = \App\Models\RentalCompany::where( 'user_id', auth()->id() )
        ->where( 'status', 'approved' )
        ->firstOrFail();

       $vehicles = Vehicle::with('images')
        ->where('rental_company_id', $company->id)
        ->latest()
        ->get();

        return view('vehicles.my', compact('vehicles'));
    }

}
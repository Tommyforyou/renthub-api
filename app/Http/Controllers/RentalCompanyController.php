<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RentalCompany;

class RentalCompanyController extends Controller
{
    public function create()
    {
        return view('company.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'phone' => 'required',
        ]);

        RentalCompany::create([
            'user_id' => auth()->id(),
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'status' => 'pending',
        ]);

        return redirect('/dashboard')
            ->with('success', 'Company registration submitted.');
    }
}

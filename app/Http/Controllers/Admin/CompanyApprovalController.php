<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalCompany;

class CompanyApprovalController extends Controller
{
    public function index()
    {
        $companies = RentalCompany::latest()->get();

        return view('admin.companies.index', compact('companies'));
    }

    public function approve($id)
    {
        $company = RentalCompany::findOrFail($id);

        $company->status = 'approved';
        $company->save();

        return back()->with('success', 'Company approved.');
    }

    public function reject($id)
    {
        $company = RentalCompany::findOrFail($id);

        $company->status = 'rejected';
        $company->save();

        return back()->with('success', 'Company rejected.');
    }
}
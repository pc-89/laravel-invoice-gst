<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyDetail;

class CompanyDetailController extends Controller
{
    public function index()
    {
        // Find existing record for the logged-in user
        $company = CompanyDetail::where('user_id', auth()->id())->first();
        return view('dashboard.user_profile', compact('company'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'address'      => 'required|string',
            'mobile'       => 'required|string',
            'email'        => 'required|email',
            'gstin'        => 'required|string',
            'state_name'   => 'required|string',
            'state_code'   => 'required|string',
            'account_name' => 'required|string',
            'account_no'   => 'required|string',
            'bank_name'    => 'required|string',
            'ifsc_code'    => 'required|string',
        ]);

        CompanyDetail::updateOrCreate(
            ['user_id' => auth()->id()],
            $request->all()
        );

        return redirect()->back()->with('success', 'Company structural data updated successfully!');
    }
}

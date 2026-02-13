<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    
    public function index()
    {
        $companies = Company::all();
        // dd($companies);
        return view('companies.index', compact('companies'));
    }

    
    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
        ]);
        // dd($validated);
        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Company created successfully!');
    }

   
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

   
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'email' => 'required|email|unique:companies,email,' . $company->id,
        ]);

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully!');
    }

    
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully!');
    }

   
    public function select(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ]);

        $user = auth()->user();
        $companyId = $request->input('company_id');

        $belongs = $user->companies()->where('company_id', $companyId)->exists();
        if (! $belongs && $user->role !== \App\Models\User::ROLE_SUPERADMIN) {
            return back()->with('error', 'You are not a member of the selected company.');
        }

        session(['company_id' => $companyId]);

        return redirect()->back();
    }
}

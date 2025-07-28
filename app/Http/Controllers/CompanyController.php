<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('name')->paginate(10);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies,code',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'additional_info' => 'nullable|string',
        ]);
        
        $validated['created_by'] = Auth::id();
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $validated['logo_path'] = $logoPath;
        }
        
        Company::create($validated);
        
        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $company = Company::with(['welders' => function($query) {
            $query->withCount(['qualificationTests', 'activeQualifications']);
        }])->with('projects')->findOrFail($id);
        
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::findOrFail($id);
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies,code,' . $company->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'additional_info' => 'nullable|string',
        ]);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $validated['logo_path'] = $logoPath;
        }
        
        $company->update($validated);
        
        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        
        // Check if there are welders associated with this company
        if($company->welders()->count() > 0) {
            return redirect()->route('companies.index')
                ->with('error', 'Cannot delete company with associated welders.');
        }
        
        // Delete company logo if exists
        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }
        
        // Detach all projects before deleting
        $company->projects()->detach();
        
        $company->delete();
        
        return redirect()->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
    
    /**
     * Show the form for managing projects for a company.
     */
    public function manageProjects(string $id)
    {
        $company = Company::with('projects')->findOrFail($id);
        $projects = Project::orderBy('name')->get();
        $companyProjects = $company->projects->pluck('id')->toArray();
        
        return view('companies.manage-projects', compact('company', 'projects', 'companyProjects'));
    }
    
    /**
     * Update the projects associated with a company.
     */
    public function updateProjects(Request $request, string $id)
    {
        $company = Company::findOrFail($id);
        
        // Validate the request
        $validated = $request->validate([
            'projects' => 'nullable|array',
            'projects.*' => 'exists:projects,id'
        ]);
        
        // Sync the projects (attach new ones, detach removed ones)
        $company->projects()->sync($request->projects ?? []);
        
        return redirect()->route('companies.show', $company->id)
            ->with('success', 'Company projects updated successfully.');
    }
}

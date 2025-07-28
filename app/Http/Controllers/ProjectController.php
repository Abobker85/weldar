<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::orderBy('name');
        
        // Filter by company if company_id is provided
        if ($request->has('company_id') && $request->company_id) {
            $query->whereHas('companies', function($q) use ($request) {
                $q->where('companies.id', $request->company_id);
            });
        }
        
        $projects = $query->paginate(10)->withQueryString();
        $companies = Company::orderBy('name')->get();
        
        return view('projects.index', compact('projects', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $validated['created_by'] = Auth::id();
        Project::create($validated);
        
        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::with('companies')->findOrFail($id);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:projects,code,' . $id,
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $project->update($validated);
        
        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        
        // Check if there are companies associated with this project before deleting
        if ($project->companies()->count() > 0) {
            return redirect()->route('projects.index')
                ->with('error', 'Cannot delete project that is associated with companies.');
        }
        
        $project->delete();
        
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
   
}

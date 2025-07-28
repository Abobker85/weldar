<?php

namespace App\Http\Controllers;

use App\Models\Welder;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WelderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = Company::orderBy('name')->pluck('name', 'id');

        if ($request->ajax()) {
            $query = Welder::query()->with(['company', 'createdBy']);
            
            // Search functionality
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('iqama_no', 'like', "%{$search}%")
                      ->orWhere('welder_no', 'like', "%{$search}%");
                });
            }
            
            // Company filter
            if ($request->has('company_id') && $request->company_id != '') {
                $query->where('company_id', $request->company_id);
            }
            
            return DataTables::of($query)
                ->addColumn('company_name', function ($welder) {
                    return $welder->company->name;
                })
                ->addColumn('created_by', function ($welder) {
                    return $welder->createdBy ? $welder->createdBy->name : 'N/A';
                })
                ->addColumn('actions', function ($welder) {
                    $viewBtn = '<a href="' . route('welders.show', $welder->id) . '" class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></a>';
                    $editBtn = '<a href="' . route('welders.edit', $welder->id) . '" class="btn btn-sm btn-primary me-2"><i class="fas fa-edit"></i></a>';
                    $qualificationBtn = '<a href="' . route('qualification-tests.create', ['welder_no' => $welder->id]) . '" class="btn btn-sm btn-success me-2"><i class="fas fa-certificate"></i> Add Qualification</a>';
                    $deleteBtn = '<form class="d-inline" action="' . route('welders.destroy', $welder->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this welder?\');">' . 
                                csrf_field() . 
                                method_field('DELETE') . 
                                '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form>';
                    return $viewBtn . $editBtn . $qualificationBtn . $deleteBtn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        return view('welders.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::orderBy('name')->pluck('name', 'id');
        $nationalities = \App\Enums\QualificationOptions::nationalities();
        return view('welders.create', compact('companies', 'nationalities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'iqama_no' => 'nullable|string|max:50|unique:welders',
            'passport_id_no' => 'nullable|string|max:50|unique:welders',
            'welder_no' => 'required|string|max:50|unique:welders',
            'company_id' => 'required|exists:companies,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'rt_report' => 'required|mimes:pdf|max:5120',
            'rt_report_serial' => 'required|string|max:100',
            'ut_report' => 'nullable|mimes:pdf|max:5120',
            'ut_report_serial' => 'nullable|string|max:100',
            'additional_info' => 'nullable|string',
            'nationality' => 'required|string|max:100',
            'gender' => 'required|string|max:20',
        ]);
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('welders', 'public');
            $validated['photo'] = $path;
        }
        if ($request->hasFile('rt_report')) {
            $rtPath = $request->file('rt_report')->store('welders/rt_reports', 'public');
            $validated['rt_report'] = $rtPath;
        }
        
        if ($request->hasFile('ut_report')) {
            $utPath = $request->file('ut_report')->store('welders/ut_reports', 'public');
            $validated['ut_report'] = $utPath;
        }
        $validated['created_by'] = Auth::id();
        Welder::create($validated);
        
        return redirect()->route('welders.index')
            ->with('success', 'Welder created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $welder = Welder::with(['company', 'qualificationTests' => function($query) {
            // $query->orderBy('date_of_expiry', 'desc');
        }])->findOrFail($id);
        
        return view('welders.show', compact('welder'));
    }

    /**
     * Show the form for editing the specified resource.
     */ 
    public function edit(string $id)
    {
        $welder = Welder::findOrFail($id);
        $companies = Company::orderBy('name')->pluck('name', 'id');
        $nationalities = \App\Enums\QualificationOptions::nationalities();
        return view('welders.edit', compact('welder', 'companies', 'nationalities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $welder = Welder::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'iqama_no' => 'nullable|string|max:50|unique:welders,iqama_no,'.$id,
            'passport_id_no' => 'nullable|string|max:50|unique:welders,passport_id_no,'.$id,
            'welder_no' => 'required|string|max:50|unique:welders,welder_no,'.$id,
            'company_id' => 'required|exists:companies,id',
            'photo' => $welder->photo ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'rt_report' => 'nullable|mimes:pdf|max:5120',
            'rt_report_serial' => 'required|string|max:100',
            'ut_report' => 'nullable|mimes:pdf|max:5120',
            'ut_report_serial' => 'nullable|string|max:100',
            'additional_info' => 'nullable|string',
            'nationality' => 'required|string|max:100',
            'gender' => 'required|string|max:20',
        ]);
        
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($welder->photo) {
                Storage::disk('public')->delete($welder->photo);
            }
            
            $path = $request->file('photo')->store('welders', 'public');
            $validated['photo'] = $path;
        }
        if ($request->hasFile('rt_report')) {
            // Delete the old rt_report if it exists
            if ($welder->rt_report) {
                Storage::disk('public')->delete($welder->rt_report);
            }
            $rtPath = $request->file('rt_report')->store('welders/rt_reports', 'public');
            $validated['rt_report'] = $rtPath;
        }
        
        if ($request->hasFile('ut_report')) {
            // Delete the old ut_report if it exists
            if ($welder->ut_report) {
                Storage::disk('public')->delete($welder->ut_report);
            }
            $utPath = $request->file('ut_report')->store('welders/ut_reports', 'public');
            $validated['ut_report'] = $utPath;
        }
        
        $welder->update($validated);
        
        return redirect()->route('welders.index')
            ->with('success', 'Welder updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $welder = Welder::findOrFail($id);
        
        // Check if there are qualification tests associated with this welder
        if($welder->qualificationTests()->count() > 0) {
            return redirect()->route('welders.index')
                ->with('error', 'Cannot delete welder with qualification records.');
        }
        
        // Delete the photo if it exists
        if ($welder->photo) {
            Storage::disk('public')->delete($welder->photo);
        }
        
        $welder->delete();
        
        return redirect()->route('welders.index')
            ->with('success', 'Welder deleted successfully.');
    }
}

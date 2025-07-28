<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics.
     */
    public function index()
    {
        // Total counts
        $totalWelders = \App\Models\Welder::count();


        //failed qualifications 
        $failedQualifications = \App\Models\QualificationTest::where('test_result', 0)->count();
        
        // Get all active qualifications with valid VT/RT results
        $activeQualifications = \App\Models\QualificationTest::where('is_active', true)
            ->where('test_result', 1)
            ->where(function($query) {
                $query->where('vt_result', 'ACC')
                      ->orWhere('rt_result', 'ACC');
            })
            ->count();
            
        // Upcoming activities (using VT date as reference)
        $expiringIn30Days = \App\Models\QualificationTest::where('is_active', true)
            ->where('test_result', 1)
            ->whereDate('vt_date', '>=', now())
            ->whereDate('vt_date', '<=', now()->addDays(30))
            ->count();
            
        $expiringIn60Days = \App\Models\QualificationTest::where('is_active', true)
            ->where('test_result', 1)
            ->whereDate('vt_date', '>', now()->addDays(30))
            ->whereDate('vt_date', '<=', now()->addDays(60))
            ->count();

        $expiringIn90Days = \App\Models\QualificationTest::where('is_active', true)
            ->where('test_result', 1)
            ->whereDate('vt_date', '>', now()->addDays(60))
            ->whereDate('vt_date', '<=', now()->addDays(90))
            ->count();

        // Qualifications by status 
        // Using tests that have dates in the past
        $expiredQualifications = \App\Models\QualificationTest::where('test_result', 1)
            ->whereDate('vt_date', '<', now())
            ->count();

        // Qualifications by certification code
        $qualificationsByCertCode = \App\Models\QualificationTest::select('qualification_code', \DB::raw('count(*) as total'))
            ->where('test_result', 1)
            ->groupBy('qualification_code')
            ->get();
            
        // Recent welders (added in last 30 days)
        $recentWelders = \App\Models\Welder::with('company')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Recent qualification tests
        $recentTests = \App\Models\QualificationTest::with('welder')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Projects by company
        $projectsByCompany = \App\Models\Company::withCount('projects')->get();

        // Return data to dashboard view
        return view('dashboard', compact(
            'totalWelders', 
            'failedQualifications',
            'activeQualifications', 
            'expiringIn30Days',
            'expiringIn60Days',
            'expiringIn90Days',
            'expiredQualifications',
            'qualificationsByCertCode',
            'recentWelders',
            'recentTests',
            'projectsByCompany'
        ));
    }
}

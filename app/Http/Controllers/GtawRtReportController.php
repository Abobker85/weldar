<?php

namespace App\Http\Controllers;

use App\Models\GtawRtReportWeldar;
use App\Models\GtawCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GtawRtReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Not needed for this implementation
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed for this implementation
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Explicitly set this to always return JSON for this endpoint
        // This will prevent redirects and always return a JSON response
        $isAjax = true;
        
        try {
            // Validate the request
            $validated = $request->validate([
                'welder_id' => 'required|exists:welders,id',
                'certificate_id' => 'required|exists:gtaw_certificates,id',
                'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
            ]);
            
            // Log request details for debugging
            Log::info('GTAW RT Report Upload Request', [
                'request_type' => $isAjax ? 'AJAX' : 'Regular Form',
                'headers' => $request->headers->all(),
                'welder_id' => $request->welder_id,
                'certificate_id' => $request->certificate_id,
                'has_file' => $request->hasFile('attachment')
            ]);
            
            // Store the file
            $path = $request->file('attachment')->store('rt-reports/gtaw', 'public');
            
            // Create the RT report record
            $report = GtawRtReportWeldar::create([
                'welder_id' => $request->welder_id,
                'certificate_id' => $request->certificate_id,
                'attachment' => $path,
            ]);
            
            // Log successful creation
            Log::info('GTAW RT Report created', ['report_id' => $report->id, 'path' => $path]);
            
            // Handle AJAX or regular request
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => 'RT Report uploaded successfully.',
                    'data' => [
                        'report_id' => $report->id,
                        'file_name' => basename($path),
                        'certificate_id' => $request->certificate_id
                    ]
                ]);
            } else {
                return redirect()->route('gtaw-certificates.show', $request->certificate_id)
                    ->with('success', 'RT Report uploaded successfully.');
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('GTAW RT Report upload error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Handle AJAX or regular request for errors
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload RT Report: ' . $e->getMessage()
                ], 500);
            } else {
                return redirect()->back()
                    ->with('error', 'Failed to upload RT Report: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

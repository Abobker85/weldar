<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Models\AppSetting;
use App\Models\Company;
use App\Models\SmawCertificate;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes using ApiController - Unified certificate endpoints
Route::get('/welders/{id}', [ApiController::class, 'getWelder'])->name('api.welders.show');
Route::get('/welders/{id}/{certificateType}', [ApiController::class, 'getWelder'])->name('api.welders.certificate');
Route::get('/welders/{id}/all-certificates/numbers', [ApiController::class, 'getAllCertificateNumbers'])->name('api.welders.all-certificates');

// Legacy routes maintained for backward compatibility
Route::get('/companies/{id}/code', [ApiController::class, 'getSmawCertificateNumber'])->name('api.smaw.certificate.code');
Route::get('/companies/{id}/fcaw-code', [ApiController::class, 'getFcawCertificateNumber'])->name('api.fcaw.certificate.code');
Route::get('/companies/{id}/saw-code', [ApiController::class, 'getSawCertificateNumber'])->name('api.saw.certificate.code');

// Get SMAW certificate number for a company
Route::get('/companies/{id}/code', function (Request $request, $id) {
    $company = Company::findOrFail($id);
    $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
    $companyCode = $company->code ? $systemCode . '-' . $company->code : $systemCode . '-AIC';
    $certificatePrefix = $companyCode . '-SMAW-';
    
    $lastCert = SmawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
        ->orderBy('certificate_no', 'desc')
        ->first();
        
    $newNumber = 1;
    if ($lastCert) {
        $lastNumber = (int) substr($lastCert->certificate_no, -4);
        $newNumber = $lastNumber + 1;
    }
    
    $newCertNo = $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    
    return response()->json([
        'prefix' => $newCertNo,
        'company_code' => $companyCode,
    ]);
})->name('api.smaw.certificate.code');

// Get FCAW certificate number for a company
Route::get('/companies/{id}/fcaw-code', function (Request $request, $id) {
    $company = Company::findOrFail($id);
    $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
    $companyCode = $company->code ? $systemCode . '-' . $company->code : $systemCode . '-AIC';
    $certificatePrefix = $companyCode . '-FCAW-';
    
    // Check if FcawCertificate model exists
    if (!class_exists('\\App\\Models\\FcawCertificate')) {
        return response()->json([
            'prefix' => $certificatePrefix . '0001',
            'company_code' => $companyCode,
        ]);
    }
    
    $lastCert = \App\Models\FcawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
        ->orderBy('certificate_no', 'desc')
        ->first();
        
    $newNumber = 1;
    if ($lastCert) {
        $lastNumber = (int) substr($lastCert->certificate_no, -4);
        $newNumber = $lastNumber + 1;
    }
    
    $newCertNo = $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    
    return response()->json([
        'prefix' => $newCertNo,
        'company_code' => $companyCode,
    ]);
});

// Get SAW certificate number for a company
Route::get('/companies/{id}/saw-code', function (Request $request, $id) {
    $company = Company::findOrFail($id);
    $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
    $companyCode = $company->code ? $systemCode . '-' . $company->code : $systemCode . '-AIC';
    $certificatePrefix = $companyCode . '-SAW-';

    // Check if SawCertificate model exists
    if (!class_exists('\\App\\Models\\SawCertificate')) {
        return response()->json([
            'prefix' => $certificatePrefix . '0001',
            'company_code' => $companyCode,
        ]);
    }

    $lastCert = \App\Models\SawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
        ->orderBy('certificate_no', 'desc')
        ->first();

    $newNumber = 1;
    if ($lastCert) {
        $lastNumber = (int) substr($lastCert->certificate_no, -4);
        $newNumber = $lastNumber + 1;
    }

    $newCertNo = $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

    return response()->json([
        'prefix' => $newCertNo,
        'company_code' => $companyCode,
    ]);
});

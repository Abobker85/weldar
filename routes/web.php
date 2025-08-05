<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\WelderController;
use App\Http\Controllers\QualificationTestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SmawCertificateController;
use App\Http\Controllers\GtawCertificateController;
use App\Http\Controllers\GtawSmawCentificateController;
use App\Http\Controllers\FcawCertificateController;
use App\Http\Controllers\SawCertificateController;
use Illuminate\Http\Request;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Public routes (no authentication required)
Route::get('verify-certificate/{id}/{code}', [SmawCertificateController::class, 'verify'])->name('smaw-certificates.verify');
Route::get('verify', [SmawCertificateController::class, 'showVerificationForm'])->name('smaw-certificates.verify-form');
Route::post('verify', [SmawCertificateController::class, 'verifyByCertificateNo'])->name('smaw-certificates.verify-submit');

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Unified route for getting welder details with certificate numbers
Route::get('/welders/{id}/details', [App\Http\Controllers\ApiController::class, 'getWelder'])->name('welders.details');

// Web API routes for direct access
// Route for getting welder data for forms - using unified ApiController
Route::get('/api/welders/{id}', [\App\Http\Controllers\ApiController::class, 'getWelder'])->name('web.api.welders.show');

// Route for getting FCAW certificate numbers - using unified ApiController
Route::get('/api/companies/{id}/fcaw-code', [\App\Http\Controllers\ApiController::class, 'getFcawCertificateNumber'])->name('web.api.fcaw.certificate.code');

// Route for generating certificate number and report numbers - using the unified ApiController
Route::get('/generate-certificate-number', function (Request $request) {
    // Get parameters from the request
    $companyId = $request->input('company_id');
    $type = $request->input('type', 'SMAW');
    
    // Use our ApiController methods to generate the numbers
    $apiController = new \App\Http\Controllers\ApiController();
    
    // Get the company
    $company = \App\Models\Company::find($companyId);
    
    if (!$company) {
        return response()->json([
            'error' => 'Company not found'
        ], 404);
    }
    
    // Generate the company code
    $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
    $companyCode = $company->code ? $systemCode . '-' . $company->code : $systemCode . '-AIC';
    
    // Generate certificate number
    $certificateNo = $apiController->generateCertificateNumber($companyCode, strtoupper($type));
    
    // Generate report numbers
    $vtReportNo = $apiController->generateReportNumber($companyCode, 'VT');
    $rtReportNo = $apiController->generateReportNumber($companyCode, 'RT');
    
    return response()->json([
        'certificate_no' => $certificateNo,
        'vt_report_no' => $vtReportNo,
        'rt_report_no' => $rtReportNo
    ]);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard & Home
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // API for generating certificate numbers
    Route::get('/api/generate-cert-number', [QualificationTestController::class, 'generateCertNumber'])->name('api.generateCertNumber');

    // Company Routes
    Route::resource('companies', CompanyController::class);
    Route::get('companies/{company}/projects', [CompanyController::class, 'manageProjects'])->name('companies.projects');
    Route::put('companies/{company}/projects', [CompanyController::class, 'updateProjects'])->name('companies.projects.update');

    // Project Routes
    Route::resource('projects', ProjectController::class);

    // Welder Routes
    Route::resource('welders', WelderController::class);

    // Qualification Test Routes
    Route::resource('qualification-tests', QualificationTestController::class);

    // Card Generation
    Route::get('/qualification-tests/{id}/card', [QualificationTestController::class, 'generateCard'])->name('qualification-tests.card');

    // Certificate Generation
    Route::get('qualification-tests/{qualification_test}/certificate', [QualificationTestController::class, 'generateCertificate'])->name('qualification-tests.certificate');
    
    // SMAW Certificate Routes
    Route::resource('smaw-certificates', SmawCertificateController::class);
    Route::get('smaw-certificates/{id}/certificate', [SmawCertificateController::class, 'generateCertificate'])->name('smaw-certificates.certificate');
    Route::get('smaw-certificates/preview', [SmawCertificateController::class, 'preview'])->name('smaw-certificates.preview');
    
    // Add these routes for SMAW certificates cards
    Route::get('/smaw-certificates/{id}/card', [App\Http\Controllers\SmawCertificateController::class, 'generateCard'])
        ->name('smaw-certificates.card');
    Route::get('/smaw-certificates/{id}/back-card', [App\Http\Controllers\SmawCertificateController::class, 'generateBackCard'])
        ->name('smaw-certificates.back-card');
    
    // GTAW Certificate Routes
    Route::resource('gtaw-certificates', GtawCertificateController::class);
    Route::get('gtaw-certificates/{id}/certificate', [App\Http\Controllers\GtawCertificateController::class, 'generateCertificate'])->name('gtaw-certificates.certificate');
    Route::get('gtaw-certificates/{id}/card', [App\Http\Controllers\GtawCertificateController::class, 'generateCard'])->name('gtaw-certificates.card');
    Route::get('gtaw-certificates/{id}/back-card', [App\Http\Controllers\GtawCertificateController::class, 'generateBackCard'])->name('gtaw-certificates.back-card');
    Route::get('gtaw-certificates/{id}/verify/{code}', [App\Http\Controllers\GtawCertificateController::class, 'verify'])->name('gtaw-certificates.verify');
    Route::get('verify-gtaw-certificate', [App\Http\Controllers\GtawCertificateController::class, 'showVerificationForm'])->name('gtaw-certificates.show-verification-form');
    Route::post('verify-gtaw-certificate', [App\Http\Controllers\GtawCertificateController::class, 'verifyByCertificateNo'])->name('gtaw-certificates.verify-by-certificate-no');
    Route::get('get-welder-details-gtaw/{id}', [App\Http\Controllers\ApiController::class, 'getWelder'])->name('gtaw-certificates.get-welder-details');
    Route::get('gtaw-certificates-preview', [App\Http\Controllers\GtawCertificateController::class, 'preview'])->name('gtaw-certificates.preview');
    
    // GTAW SMAW Certificate routes
    Route::resource('gtaw-smaw-certificates', GtawSmawCentificateController::class);
    Route::get('gtaw-smaw-certificates/{id}/certificate', [GtawSmawCentificateController::class, 'generateCertificate'])->name('gtaw-smaw-certificates.certificate');
    Route::get('gtaw-smaw-certificates/{id}/card', [GtawSmawCentificateController::class, 'generateCard'])->name('gtaw-smaw-certificates.card');
    Route::get('gtaw-smaw-certificates/{id}/back-card', [GtawSmawCentificateController::class, 'generateBackCard'])->name('gtaw-smaw-certificates.back-card');
    Route::get('gtaw-smaw-certificates/{id}/verify/{code}', [GtawSmawCentificateController::class, 'verify'])->name('gtaw-smaw-certificates.verify');
    Route::get('gtaw-smaw-certificate-verification', [GtawSmawCentificateController::class, 'showVerificationForm'])->name('gtaw-smaw-certificates.verification-form');
    Route::post('gtaw-smaw-certificate-verification', [GtawSmawCentificateController::class, 'verifyByCertificateNo'])->name('gtaw-smaw-certificates.verify-by-certificate-no');
    Route::get('gtaw-smaw-certificates-preview', [GtawSmawCentificateController::class, 'preview'])->name('gtaw-smaw-certificates.preview');
    Route::get('welders/{id}/gtaw-smaw-details', [App\Http\Controllers\ApiController::class, 'getWelder'])->name('gtaw-smaw.welder-details');
    
    // FCAW Certificate routes
    Route::resource('fcaw-certificates', FcawCertificateController::class);
    Route::get('fcaw-certificates/{id}/certificate', [FcawCertificateController::class, 'generateCertificate'])->name('fcaw-certificates.certificate');
    Route::get('fcaw-certificates/{id}/card', [FcawCertificateController::class, 'generateCard'])->name('fcaw-certificates.card');
    Route::get('fcaw-certificates/{id}/back-card', [FcawCertificateController::class, 'generateBackCard'])->name('fcaw-certificates.back-card');
    Route::get('fcaw-certificates/{id}/verify/{code}', [FcawCertificateController::class, 'verify'])->name('fcaw-certificates.verify');
    Route::get('fcaw-certificate-verification', [FcawCertificateController::class, 'showVerificationForm'])->name('fcaw-certificates.verification-form');
    Route::post('fcaw-certificate-verification', [FcawCertificateController::class, 'verifyByCertificateNo'])->name('fcaw-certificates.verify-by-certificate-no');
    Route::get('fcaw-certificates-preview', [FcawCertificateController::class, 'preview'])->name('fcaw-certificates.preview');
    Route::get('welders/{id}/fcaw-details', [App\Http\Controllers\ApiController::class, 'getWelder'])->name('fcaw.welder-details');

    // SAW Certificate Routes
    Route::resource('saw-certificates', SawCertificateController::class);
    Route::get('saw-certificates/{id}/certificate', [SawCertificateController::class, 'generateCertificate'])->name('saw-certificates.certificate');
    Route::get('saw-certificates/{id}/card', [SawCertificateController::class, 'generateCard'])->name('saw-certificates.card');
    Route::get('saw-certificates/{id}/back-card', [SawCertificateController::class, 'generateBackCard'])->name('saw-certificates.back-card');
    Route::get('welders/{id}/details', [SawCertificateController::class, 'getWelderDetails'])->name('welders.details');
    Route::get('verify-saw-certificate', [SawCertificateController::class, 'showVerificationForm'])->name('saw-certificates.verify-form');
    Route::post('verify-saw-certificate', [SawCertificateController::class, 'verifyByCertificateNo'])->name('saw-certificates.verify-submit');
    Route::get('verify-saw/{id}/{code}', [SawCertificateController::class, 'verify'])->name('saw-certificates.verify');

    // Profile routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Admin user management
Route::middleware(['auth'])->group(function () {
    Route::group(['middleware' => function ($request, $next) {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->is_admin) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this resource.');
        }
        return $next($request);
    }], function () {
        Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.users.resetPassword');
    });
});

// Admin settings routes
Route::middleware(['auth'])->group(function () {




});    Route::post('/admin/cert-settings', [AppSettingsController::class, 'updateCertSettings'])->name('admin.certsettings.update');    Route::post('/admin/settings', [AppSettingsController::class, 'update'])->name('admin.settings.update');    Route::get('/admin/settings', [AppSettingsController::class, 'edit'])->name('admin.settings');    Route::group(['middleware' => function ($request, $next) {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || !$user->is_admin) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this resource.');
        }
        return $next($request);
    }], function () {
        Route::get('/admin/settings', [AppSettingsController::class, 'edit'])->name('admin.settings.edit');
        Route::post('/admin/settings', [AppSettingsController::class, 'update'])->name('admin.settings.update');
    });

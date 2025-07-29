<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Welder;
use App\Models\Company;
use App\Models\AppSetting;
use App\Models\SmawCertificate;
use App\Models\FcawCertificate;
use App\Models\SawCertificate;

class ApiController extends Controller
{
    /**
     * Get welder details by ID with all certificate numbers
     * @param int $id Welder ID
     * @param string $certificateType Optional - specific certificate type to generate (smaw, gtaw, gtaw_smaw, gtaw-smaw, fcaw)
     */
    public function getWelder($id, $certificateType = null)
    {
        // Normalize certificate type (handle both gtaw_smaw and gtaw-smaw formats)
        if ($certificateType) {
            $certificateType = str_replace('_', '-', strtoupper($certificateType));
        }
        $welder = Welder::findOrFail($id);
        $company = $welder->company;
        
        // Prepare photo URL if photo exists
        $photoUrl = null;
        $photoPath = $welder->photo;
        if ($photoPath) {
            $photoUrl = url('storage/' . $photoPath);
        }
        
        // Get system code and company code
        $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
        $companyCode = $company && $company->code ? $systemCode . '-' . $company->code : $systemCode . '-AIC';
        
        $response = [
            'welder' => [
                'id' => $welder->id,
                'name' => $welder->name,
                'welder_id' => $welder->welder_no,
                'iqama_no' => $welder->iqama_no,
                'passport_no' => $welder->passport_id_no,
                'company_id' => $welder->company_id,
                'company_name' => $company ? $company->name : '',
                'photo' => $photoPath,
                'photo_path' => $photoUrl,
            ],
            'company_code' => $companyCode
        ];
        
        // Generate certificate numbers based on certificate type
        if ($certificateType === null || $certificateType === 'all') {
            // Generate all certificate types
            $response['smaw_certificate'] = $this->generateCertificateNumber($companyCode, 'SMAW');
            $response['gtaw_certificate'] = $this->generateCertificateNumber($companyCode, 'GTAW');
            $response['fcaw_certificate'] = $this->generateCertificateNumber($companyCode, 'FCAW');
            $response['saw_certificate'] = $this->generateCertificateNumber($companyCode, 'SAW');
            $response['gtaw_smaw_certificate'] = $this->generateCertificateNumber($companyCode, 'GTAW-SMAW');
            
            // Generate report numbers
            $response['vt_report_no'] = $this->generateReportNumber($companyCode, 'VT');
            $response['rt_report_no'] = $this->generateReportNumber($companyCode, 'RT');
            $response['ut_report_no'] = $this->generateReportNumber($companyCode, 'UT');
        } else {
            // Generate specific certificate type
            $response['certificate_no'] = $this->generateCertificateNumber($companyCode, strtoupper($certificateType));
            $response['vt_report_no'] = $this->generateReportNumber($companyCode, 'VT');
            $response['rt_report_no'] = $this->generateReportNumber($companyCode, 'RT');
            $response['ut_report_no'] = $this->generateReportNumber($companyCode, 'UT');
        }
        
        return response()->json($response);
    }
    
    /**
     * Get SMAW certificate number for a company
     */
    public function getSmawCertificateNumber($id)
    {
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
    }

    /**
     * Get SAW certificate number for a company
     */
    public function getSawCertificateNumber($id)
    {
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

        $lastCert = SawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
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
    }
    
    /**
     * Get FCAW certificate number for a company
     */
    public function getFcawCertificateNumber($id)
    {
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
        
        $lastCert = FcawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
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
    }
    
    /**
     * Generate certificate number based on certificate type
     * 
     * @param string $companyCode Company code
     * @param string $certType Certificate type (SMAW, GTAW, FCAW, GTAW-SMAW, SAW)
     * @return string Generated certificate number
     */
    public function generateCertificateNumber($companyCode, $certType)
    {
        $certificatePrefix = $companyCode . '-' . $certType . '-';
        $modelClass = null;
        
        // Determine which model to use based on certificate type
        switch ($certType) {
            case 'SMAW':
                $modelClass = 'App\\Models\\SmawCertificate';
                break;
            case 'GTAW':
                $modelClass = 'App\\Models\\GtawCertificate';
                break;
            case 'FCAW':
                $modelClass = 'App\\Models\\FcawCertificate';
                break;
            case 'SAW':
                $modelClass = 'App\\Models\\SawCertificate';
                break;
            case 'GTAW-SMAW':
                $modelClass = 'App\\Models\\GtawSmawCentificate';
                break;
            default:
                return $certificatePrefix . '0001';
        }
        
        if (!class_exists($modelClass)) {
            return $certificatePrefix . '0001';
        }
        
        $lastCert = app($modelClass)::where('certificate_no', 'like', $certificatePrefix . '%')
            ->orderBy('certificate_no', 'desc')
            ->first();
            
        $newNumber = 1;
        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_no, -4);
            $newNumber = $lastNumber + 1;
        }
        
        return $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Generate report number based on report type
     * 
     * @param string $companyCode Company code
     * @param string $reportType Report type (VT, RT, UT)
     * @return string Generated report number
     */
    public function generateReportNumber($companyCode, $reportType)
    {
        $reportPrefix = $companyCode . '-' . $reportType . '-';
        
        // Use all certificate models to find the latest report number
        $models = [
            'App\\Models\\SmawCertificate',
            'App\\Models\\GtawCertificate', 
            'App\\Models\\FcawCertificate',
            'App\\Models\\SawCertificate',
            'App\\Models\\GtawSmawCentificate'
        ];
        
        $lastNumber = 0;
        
        foreach ($models as $modelClass) {
            if (class_exists($modelClass)) {
                $fieldName = strtolower($reportType) . '_report_no';
                
                // Check if the model has the field
                $model = app($modelClass);
                if (!in_array($fieldName, $model->getFillable())) {
                    continue;
                }
                
                $lastReport = $model::where($fieldName, 'like', $reportPrefix . '%')
                    ->orderBy($fieldName, 'desc')
                    ->first();
                
                if ($lastReport && $lastReport->{$fieldName}) {
                    $currentNumber = (int) substr($lastReport->{$fieldName}, -4);
                    if ($currentNumber > $lastNumber) {
                        $lastNumber = $currentNumber;
                    }
                }
            }
        }
        
        $newNumber = $lastNumber + 1;
        return $reportPrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Generate all certificate numbers for a welder
     * Unified method for all certificate types
     * 
     * @param int $welderId Welder ID
     * @return array All certificate numbers and report numbers
     */
    public function getAllCertificateNumbers($welderId)
    {
        $welder = Welder::with('company')->findOrFail($welderId);
        $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
        $companyCode = $welder->company && $welder->company->code ? 
            $systemCode . '-' . $welder->company->code : 
            $systemCode . '-AIC';
        
        return [
            // Certificate numbers
            'smaw_certificate_no' => $this->generateCertificateNumber($companyCode, 'SMAW'),
            'gtaw_certificate_no' => $this->generateCertificateNumber($companyCode, 'GTAW'),
            'fcaw_certificate_no' => $this->generateCertificateNumber($companyCode, 'FCAW'),
            'saw_certificate_no' => $this->generateCertificateNumber($companyCode, 'SAW'),
            'gtaw_smaw_certificate_no' => $this->generateCertificateNumber($companyCode, 'GTAW-SMAW'),
            
            // Report numbers
            'vt_report_no' => $this->generateReportNumber($companyCode, 'VT'),
            'rt_report_no' => $this->generateReportNumber($companyCode, 'RT'),
            'ut_report_no' => $this->generateReportNumber($companyCode, 'UT'),
            
            // Company info
            'company_code' => $companyCode
        ];
    }
}

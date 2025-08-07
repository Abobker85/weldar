<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SawCertificate extends Model
{
    use SoftDeletes;
    protected $fillable = [
    // Existing fields
    'certificate_no', 'welder_id', 'revision_no', 'company_id', 'wps_followed', 'test_date',
    'base_metal_spec', 'diameter', 'thickness', 'dia_thickness',
    'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
    
    // ADDED: Frontend field mappings
    'base_metal_p_no_from', 'base_metal_p_no_to', 'base_metal_p_no',
    'filler_metal_sfa_spec', 'filler_metal_classification',
    'filler_spec', 'filler_class', 'filler_f_no',
    
    // Position and backing
    'test_position', 'position_range', 'backing', 'backing_range',
    
    // Machine variables
    'welding_type', 'welding_process', 'visual_control_type', 
    'joint_tracking', 'consumable_inserts', 'passes_per_side',
    
    // Range fields
    'diameter_range', 'p_number_range', 'visual_control_range',
    'joint_tracking_range', 'passes_range', 'f_number_range',
    'vertical_progression', 'vertical_progression_range',
    
    // Test results
    'rt', 'ut', 'rt_selected', 'ut_selected', 'rt_doc_no',
    'vt_report_no', 'rt_report_no', 'visual_examination_result',
    'alternative_volumetric_result',
    
    // Personnel
    'film_evaluated_by', 'evaluated_company', 'mechanical_tests_by',
    'lab_test_no', 'welding_supervised_by', 'supervised_by', 'supervised_company',
    
    // Organization
    'test_witnessed_by', 'witness_name', 'witness_date', 'witness_signature',
    
    // Signatures and photos
    'photo_path', 'signature_data', 'inspector_signature_data',
    
    // Additional
    'certification_text', 'verification_code', 'test_result', 'created_by',
    
    // Automatic welding variables
    'automatic_welding_type', 'automatic_welding_type_range',
    'automatic_welding_process', 'automatic_welding_process_range',
    'filler_metal_used_auto', 'filler_metal_used_auto_range',
    'laser_type', 'laser_type_range', 'drive_type', 'drive_type_range',
    'vacuum_type', 'vacuum_type_range', 'arc_voltage_control',
    'arc_voltage_control_range', 'position_actual', 'consumable_inserts_range',
    
    // Additional test fields
    'additional_type_1', 'additional_result_1', 'test_type_2', 'test_result_2',
    'additional_type_2', 'additional_result_2', 'fillet_fracture_test',
    'defects_length_percent', 'macro_examination', 'fillet_size',
    'other_tests', 'concavity_convexity',
    
    // Confirmation fields
    'confirm_date_1', 'confirm_position_1', 'confirm_date_2', 'confirm_position_2',
    'confirm_date_3', 'confirm_position_3',
];

    protected $casts = [
        'test_coupon' => 'boolean',
        'production_weld' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'rt' => 'boolean',
        'ut' => 'boolean',
        'test_date' => 'date',
        'inspector_date' => 'date'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($certificate) {
            // Generate a unique verification code if not already set
            if (empty($certificate->verification_code)) {
                $certificate->verification_code = Str::random(12);
            }
        });
    }

    /**
     * Get the welder that owns this certificate.
     */
    public function welder()
    {
        return $this->belongsTo(Welder::class);
    }

    /**
     * Get the company associated with this certificate.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created this certificate.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function rtReports()
    {
        return $this->hasMany(SawRtReportWeldar::class, 'certificate_id');
    }

    public function rtReport()
    {
        return $this->hasOne(SawRtReportWeldar::class, 'certificate_id');
    }
}

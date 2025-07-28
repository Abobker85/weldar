<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SmawCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'welder_id',
        'company_id',
        'certificate_no',
        'verification_code',
        'wps_followed',
        'revision_no',
        'test_date',
        'base_metal_spec',
        'smaw_yes',
        'plate_specimen',
        'pipe_specimen',
        'pipe_diameter_type',
        'pipe_diameter_manual',
        'diameter_range',
        'diameter_range_manual',
        'base_metal_p_no',
        'base_metal_p_no_manual',
        'p_number_range',
        'test_coupon',
        'production_weld',
        'p_number_range_manual',
        'smaw_thickness',
        'smaw_thickness_range',
        'diameter',
        'thickness',
        
        'test_position',
        'position_range',
        'position_range_manual',
        'backing',
        'backing_manual',
        'backing_range',
        'backing_range_manual',
        'filler_spec',
        'filler_spec_manual',
        'filler_spec_range',
        'filler_class',
        'filler_class_range',
        'filler_class_manual',
        'filler_f_no',
        'filler_f_no_manual',
        'f_number_range',
        'f_number_range_manual',
        'vertical_progression',
        'vertical_progression_range',
        'vt_report_no', // Added VT report number
        'visual_examination_result', // Added visual examination result
        'rt_report_no',
        'rt_doc_no',
        'inspector_name',
        'inspector_date',
        'test_result',
        'photo_path',
        'created_by',
        
        // Additional fields needed to match the form
        'fuel_gas',
        'fuel_gas_range',
        'backing_gas',
        'backing_gas_range',
        'transfer_mode',
        'transfer_mode_range',
        'gtaw_current',
        'gtaw_current_range',
        'equipment_type',
        'equipment_type_range',
        'technique',
        'technique_range',
        'oscillation',
        'oscillation_value',
        'oscillation_range',
        'operation_mode',
        'operation_mode_range',
        'consumable_insert',
        'consumable_insert_range',
        'filler_product_form',
        'filler_product_form_range',
        'deposit_thickness',
        'deposit_thickness_range',
        
        // Test result fields
        'fillet_fracture_test',
        'defects_length',
        'rt',
        'ut',
        'fillet_welds_plate',
        'fillet_welds_pipe',
        'pipe_macro_fusion',
        'plate_macro_fusion',
        'transverse_face_root',
        'longitudinal_bends',
        'side_bends',
        'pipe_bend_corrosion',
        'plate_bend_corrosion',
        'macro_exam',
        'fillet_size',
        'other_tests',
        'concavity_convexity',
        'additional_type_1',
        'additional_result_1',
        'additional_type_2',
        'additional_result_2',
        
        // Confirmation fields
        'confirm_date1',
        'confirm_title1',
        'confirm_date2',
        'confirm_title2',
        'confirm_date3',
        'confirm_title3',
        
        // Personnel information
        'evaluated_by',
        'evaluated_company',
        'mechanical_tests_by',
        'lab_test_no',
        'supervised_by',
        'supervised_company',
        'certification_text',
        'signature_data', // Welder's signature
        'inspector_signature_data', // Inspector's signature
    ];

    protected $casts = [
        'test_date' => 'date',
        'inspector_date' => 'date',
        'smaw_yes' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'test_result' => 'boolean',
        'rt' => 'boolean',
        'ut' => 'boolean',
        'fillet_welds_plate' => 'boolean',
        'fillet_welds_pipe' => 'boolean',
        'pipe_macro_fusion' => 'boolean',
        'plate_macro_fusion' => 'boolean',
        'confirm_date1' => 'date',
        'confirm_date2' => 'date',
        'confirm_date3' => 'date',
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
}


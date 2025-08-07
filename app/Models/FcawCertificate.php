<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FcawCertificate extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'certificate_no',
        'welder_id',
        'revision_no',
        'company_id',
        'wps_followed',
        'test_date',
        'base_metal_spec',
        'diameter',
        'thickness',
        'test_coupon',
        'production_weld',
        'plate_specimen',
        'pipe_specimen',
        'pipe_diameter_type',
        'pipe_diameter_manual',
        'diameter_range',
        'base_metal_p_no',
        'base_metal_p_no_manual',
        'p_number_range',
        'p_number_range_manual',
        'test_position',
        'position_range_manual',
        'position_range',
        'backing',
        'backing_manual',
        'backing_range',
        'backing_gas',
        'backing_gas_range',
        'filler_spec',
        'filler_spec_range',
        'filler_spec_manual',
        'filler_class',
        'filler_class_range',
        'filler_class_manual',
        'filler_f_no',
        'filler_f_no_range',
        'filler_f_no_manual',
        'f_number_range',
        'vertical_progression',
        'vertical_progression_range',
        'inspector_name',
        'inspector_date',
        'photo_path',
        'certification_text',
        'signature_data',
        'inspector_signature_data',
        'transfer_mode',
        'transfer_mode_range',
        'equipment_type',
        'equipment_type_range',
        'technique',
        'technique_range',
        'oscillation',
        'oscillation_value',
        'oscillation_range',
        'operation_mode',
        'fcaw_thickness',
        'fcaw_thickness_range',
        'deposit_thickness',
        'deposit_thickness_range',
        'operation_mode_range',
        'rt',
        'ut',
        'vt_report_no',
        'rt_report_no',
        'rt_doc_no',
        'visual_examination_result',
        'evaluated_by',
        'evaluated_company',
        'mechanical_tests_by',
        'lab_test_no',
        'supervised_by',
        'supervised_company',
        'verification_code',
        'test_result',
        'created_by'
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

    public function rtReport()
    {
        return $this->hasOne(FcawRtReportWeldar::class, 'certificate_id');
    }
}

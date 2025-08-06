<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtawCertificate extends Model
{
    protected $fillable = [
        'certificate_no',
        'welder_id',
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
          'deposit_thickness',
          'p_number_range_manual',
        'deposit_thickness_range',
          'signature_data',
        'inspector_signature_data',
        'gtaw_yes',
        'gtaw_thickness',
        'gtaw_thickness_range',
        'gtaw_process',
        'test_position',
        'position_range_manual',
        'position_range',
        'backing',
        'backing_manual',
        'backing_range',
        'filler_spec',
        'filler_spec_manual',
        'filler_class',
        'filler_class_manual',
        'filler_f_no',
        'filler_f_no_manual',
        'f_number_range',
        'vertical_progression',
        'vertical_progression_range',
        'inspector_name',
        'inspector_date',
        'photo_path',
        'certification_text',
        'fuel_gas',
        'fuel_gas_range',
        'backing_gas',
        'backing_gas_range',
        'gtaw_polarity',
        'gtaw_polarity_range',
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
     * Get the welder associated with the certificate
     */
    public function welder()
    {
        return $this->belongsTo(Welder::class);
    }

    /**
     * Get the company associated with the certificate
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created the certificate
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

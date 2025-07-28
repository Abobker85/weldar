<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QualificationTest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'welder_id',
        'company_id',
        'qualification_type',
        'sr_no',
        'work_order_no',
        'location',
        'passport_id_no',
        'welder_no',
        'wps_no',
        'welding_process',
        'test_coupon',
        'dia_inch',
        'qualified_dia_inch',
        'coupon_material',
        'qualified_material',
        'coupon_thickness_mm',
        'deposit_thickness',
        'qualified_thickness_range',
        'welding_positions',
        'qualified_position',
        'filler_metal_f_no',
        'aws_spec_no',
        'filler_metal_classif',
        'backing',
        'qualified_backing',
        'electric_char',
        'qualified_ec',
        // Joint diagram details
        'joint_diagram_path',
        'joint_type',
        'joint_description',
        'joint_angle',
        'joint_total_angle',
        'root_gap',
        'root_face',
        'pipe_outer_diameter',
        'base_metal_p_no',
        'filler_metal_form',
        'inert_gas_backing',
        'gtaw_thickness',
        'smaw_thickness',
        'vertical_progression',
        // SMAW-specific fields
        'smaw_yes',
        'plate_specimen',
        'pipe_specimen',
        'pipe_diameter_type',
        'pipe_diameter_manual',
        'diameter_range',
        'diameter_range_manual',
        'base_metal_spec',
        'wps_followed',
        'filler_spec',
        'filler_class',
        'filler_f_no',
        'f_number_range',
        'f_number_range_manual',
        'vertical_progression_range',
        'inspector_name',
        'inspector_date',
        'certificate_type',
        // Test dates and results
        'test_date',
        'vt_date',
        'vt_report_no',
        'vt_result',
        'rt_date',
        'rt_report_no',
        'rt_result',
        'cert_no',
        'qualification_code',
        'remarks',
        'is_active',
        'test_result',
        'created_by',
    ];
    
    protected $casts = [
        'test_date' => 'date',
        'vt_date' => 'date',
        'rt_date' => 'date',
        'inspector_date' => 'date',
        'is_active' => 'boolean',
        'test_result' => 'boolean',
        'smaw_yes' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'coupon_thickness_mm' => 'decimal:2'
    ];

    /**
     * Get the welder that owns this qualification test.
     */
    public function welder()
    {
        return $this->belongsTo(Welder::class);
    }
    
    /**
     * Check if the qualification has a valid VT and RT result.
     */
    public function isValid()
    {
        return $this->vt_result === 'ACC' && $this->rt_result === 'ACC';
    }
    
    /**
     * Get the test date.
     */
    public function getTestDate()
    {
        return $this->test_date ?? $this->vt_date ?? $this->rt_date;
    }

    /**
     * Get the user who created this qualification test.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the company associated with this qualification test.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

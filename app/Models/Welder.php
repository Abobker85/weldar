<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Welder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'iqama_no',
        'welder_no',
        'passport_id_no',
        'company_id',
        'photo',
        'rt_report',
        'rt_report_serial',
        'ut_report',
        'ut_report_serial',
        'additional_info',
        'nationality',
        'gender',
        'created_by',
    ];

    /**
     * Get the company this welder belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the qualification tests for this welder.
     */
    public function qualificationTests()
    {
        return $this->hasMany(QualificationTest::class);
    }
    
    /**
     * Get the active qualification tests for this welder.
     */
    public function activeQualifications()
    {
        return $this->hasMany(QualificationTest::class)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('vt_result', 'ACC')
                      ->orWhere('rt_result', 'ACC');
            });
    }

    /**
     * Get the user who created this welder.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'code', // Add code to fillable
        'logo_path',
        'address',
        'contact_person',
        'phone',
        'email',
        'additional_info',
        'created_by',
    ];

    /**
     * Get the welders associated with this company.
     */
    public function welders()
    {
        return $this->hasMany(Welder::class);
    }
    
    /**
     * Get the projects associated with this company.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Get the user who created this company.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the URL for the company logo or return a default logo.
     *
     * @return string
     */
    public function getLogoUrl()
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }
        
        // Return a default logo URL
        return asset('images/default-company.png');
    }
}

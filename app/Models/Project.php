<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'description',
        'start_date',
        'end_date',
        'created_by',
    ];
    
    /**
     * Get the companies associated with this project.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    /**
     * Get the user who created this project.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

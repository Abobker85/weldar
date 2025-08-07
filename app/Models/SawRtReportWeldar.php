<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SawRtReportWeldar extends Model
{
    use HasFactory;

    protected $fillable = [
        'welder_id',
        'certificate_id',
        'attachment',
    ];

    public function welder()
    {
        return $this->belongsTo(Welder::class);
    }

    public function sawCertificate()
    {
        return $this->belongsTo(SawCertificate::class, 'certificate_id');
    }
}

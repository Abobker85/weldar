<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GtawRtReportWeldar extends Model
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

    public function gtawCertificate()
    {
        return $this->belongsTo(GtawCertificate::class, 'certificate_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FcawRtReportWeldar extends Model
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

    public function fcawCertificate()
    {
        return $this->belongsTo(FcawCertificate::class, 'certificate_id');
    }
}

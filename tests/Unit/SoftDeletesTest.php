<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Company;
use App\Models\Welder;
use App\Models\Project;
use App\Models\QualificationTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoftDeletesTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_use_soft_deletes(): void
    {
        // Test Company soft delete
        $company = Company::create([
            'name' => 'Test Company Soft Delete',
            'code' => 'TSD-01',
        ]);
        $companyId = $company->id;
        
        $company->delete();
        $this->assertSoftDeleted($company);
        $this->assertNull(Company::find($companyId));
        $this->assertNotNull(Company::withTrashed()->find($companyId));

        // Test Project soft delete
        $project = Project::create([
            'name' => 'Test Project Soft Delete',
            'code' => 'PROJ-01',
        ]);
        $projectId = $project->id;
        
        $project->delete();
        $this->assertSoftDeleted($project);
        $this->assertNull(Project::find($projectId));
        $this->assertNotNull(Project::withTrashed()->find($projectId));

        // Test Welder soft delete
        $welder = Welder::create([
            'name' => 'John Doe',
            'passport_id_no' => 'TEST-PASS-001',
            'company_id' => $company->id,
            'rt_report' => 'RT-TEST-001',
            'rt_report_serial' => 'RTS-001',
        ]);
        $welderId = $welder->id;
        
        $welder->delete();
        $this->assertSoftDeleted($welder);
        $this->assertNull(Welder::find($welderId));
        $this->assertNotNull(Welder::withTrashed()->find($welderId));

        // Test QualificationTest soft delete
        $qualification = QualificationTest::create([
            'welder_no' => $welder->id,
            'cert_no' => 'CERT-TEST-001',
            'test_date' => now(),
        ]);
        $qualificationId = $qualification->id;
        
        $qualification->delete();
        $this->assertSoftDeleted($qualification);
        $this->assertNull(QualificationTest::find($qualificationId));
        $this->assertNotNull(QualificationTest::withTrashed()->find($qualificationId));
    }
}

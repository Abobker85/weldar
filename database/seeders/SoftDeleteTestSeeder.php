<?php

// Test the soft delete functionality of all models
namespace Database\Seeders;

use App\Models\Company;
use App\Models\Project;
use App\Models\Welder;
use App\Models\QualificationTest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoftDeleteTestSeeder extends Seeder
{
    public function run()
    {
        // First, create instances of each model
        $company = new Company();
        $company->name = 'Test Soft Delete Company';
        $company->code = 'SFT-001';
        $company->save();
        $companyId = $company->id;
        echo "Company created with ID: {$companyId}\n";

        $project = new Project();
        $project->name = 'Test Soft Delete Project';
        $project->code = 'PROJ-SFT-001';
        $project->save();
        $projectId = $project->id;
        echo "Project created with ID: {$projectId}\n";

        $welder = new Welder();
        $welder->name = 'John Smith Soft Delete';
        $welder->passport_id_no = 'PASS-SFT-001';
        $welder->company_id = $companyId;
        $welder->rt_report = 'RT-SFT-001';
        $welder->rt_report_serial = 'RTS-SFT-001';
        $welder->save();
        $welderId = $welder->id;
        echo "Welder created with ID: {$welderId}\n";

        $test = new QualificationTest();
        $test->welder_no = $welderId;
        $test->cert_no = 'CERT-SFT-001';
        $test->test_date = now();
        $test->save();
        $testId = $test->id;
        echo "Qualification Test created with ID: {$testId}\n";

        // Now, delete them to test soft delete
        $company->delete();
        $project->delete();
        $welder->delete();
        $test->delete();
        echo "All records soft deleted\n";

        // Verify they are soft deleted and still retrievable with withTrashed()
        $deletedCompany = Company::withTrashed()->find($companyId);
        echo "Can retrieve deleted company: " . ($deletedCompany ? "Yes (ID: {$deletedCompany->id})" : "No") . "\n";

        $deletedProject = Project::withTrashed()->find($projectId);
        echo "Can retrieve deleted project: " . ($deletedProject ? "Yes (ID: {$deletedProject->id})" : "No") . "\n";

        $deletedWelder = Welder::withTrashed()->find($welderId);
        echo "Can retrieve deleted welder: " . ($deletedWelder ? "Yes (ID: {$deletedWelder->id})" : "No") . "\n";

        $deletedTest = QualificationTest::withTrashed()->find($testId);
        echo "Can retrieve deleted qualification test: " . ($deletedTest ? "Yes (ID: {$deletedTest->id})" : "No") . "\n";

        // Check regular queries don't return the soft deleted records
        $regularCompany = Company::find($companyId);
        echo "Regular query returns deleted company: " . ($regularCompany ? "Yes" : "No") . "\n";

        $regularProject = Project::find($projectId);
        echo "Regular query returns deleted project: " . ($regularProject ? "Yes" : "No") . "\n";

        $regularWelder = Welder::find($welderId);
        echo "Regular query returns deleted welder: " . ($regularWelder ? "Yes" : "No") . "\n";

        $regularTest = QualificationTest::find($testId);
        echo "Regular query returns deleted qualification test: " . ($regularTest ? "Yes" : "No") . "\n";

        // Restore a record
        $deletedCompany->restore();
        echo "Company restored\n";

        $restoredCompany = Company::find($companyId);
        echo "Can retrieve restored company: " . ($restoredCompany ? "Yes (ID: {$restoredCompany->id})" : "No") . "\n";

        // Clean up created records
        if ($restoredCompany) {
            $restoredCompany->forceDelete();
        }
        
        if ($deletedProject) {
            $deletedProject->forceDelete();
        }
        
        if ($deletedWelder) {
            $deletedWelder->forceDelete();
        }
        
        if ($deletedTest) {
            $deletedTest->forceDelete();
        }
        
        echo "Test completed and records cleaned up\n";
    }
}

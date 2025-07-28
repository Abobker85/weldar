<?php

// First, create instances of each model
$company = new \App\Models\Company();
$company->name = 'Test Soft Delete Company';
$company->code = 'SFT-001';
$company->save();
echo "Company created with ID: {$company->id}\n";

$project = new \App\Models\Project();
$project->name = 'Test Soft Delete Project';
$project->code = 'PROJ-SFT-001';
$project->save();
echo "Project created with ID: {$project->id}\n";

$welder = new \App\Models\Welder();
$welder->name = 'John Smith Soft Delete';
$welder->passport_id_no = 'PASS-SFT-001';
$welder->company_id = $company->id;
$welder->rt_report = 'RT-SFT-001';
$welder->rt_report_serial = 'RTS-SFT-001';
$welder->save();
echo "Welder created with ID: {$welder->id}\n";

$test = new \App\Models\QualificationTest();
$test->welder_no = $welder->id;
$test->cert_no = 'CERT-SFT-001';
$test->test_date = now();
$test->save();
echo "Qualification Test created with ID: {$test->id}\n";

// Now, delete them to test soft delete
$company->delete();
$project->delete();
$welder->delete();
$test->delete();
echo "All records soft deleted\n";

// Verify they are soft deleted and still retrievable with withTrashed()
$deletedCompany = \App\Models\Company::withTrashed()->find($company->id);
echo "Can retrieve deleted company: " . ($deletedCompany ? "Yes (ID: {$deletedCompany->id})" : "No") . "\n";

$deletedProject = \App\Models\Project::withTrashed()->find($project->id);
echo "Can retrieve deleted project: " . ($deletedProject ? "Yes (ID: {$deletedProject->id})" : "No") . "\n";

$deletedWelder = \App\Models\Welder::withTrashed()->find($welder->id);
echo "Can retrieve deleted welder: " . ($deletedWelder ? "Yes (ID: {$deletedWelder->id})" : "No") . "\n";

$deletedTest = \App\Models\QualificationTest::withTrashed()->find($test->id);
echo "Can retrieve deleted qualification test: " . ($deletedTest ? "Yes (ID: {$deletedTest->id})" : "No") . "\n";

// Check regular queries don't return the soft deleted records
$regularCompany = \App\Models\Company::find($company->id);
echo "Regular query returns deleted company: " . ($regularCompany ? "Yes" : "No") . "\n";

$regularProject = \App\Models\Project::find($project->id);
echo "Regular query returns deleted project: " . ($regularProject ? "Yes" : "No") . "\n";

$regularWelder = \App\Models\Welder::find($welder->id);
echo "Regular query returns deleted welder: " . ($regularWelder ? "Yes" : "No") . "\n";

$regularTest = \App\Models\QualificationTest::find($test->id);
echo "Regular query returns deleted qualification test: " . ($regularTest ? "Yes" : "No") . "\n";

// Restore a record
$deletedCompany->restore();
echo "Company restored\n";

$restoredCompany = \App\Models\Company::find($company->id);
echo "Can retrieve restored company: " . ($restoredCompany ? "Yes (ID: {$restoredCompany->id})" : "No") . "\n";

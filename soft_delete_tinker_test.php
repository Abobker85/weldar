// Test script for soft delete
use App\Models\Company;
use App\Models\Project;
use App\Models\Welder;
use App\Models\QualificationTest;

// Create a company
$company = new Company();
$company->name = 'Test Soft Delete Company';
$company->code = 'SFT-001';
$company->save();
echo "Company created with ID: {$company->id}\n";

// Delete the company (soft delete)
$company->delete();
echo "Company soft-deleted\n";

// Try to retrieve via normal query
$normalQuery = Company::find($company->id);
echo "Can find with normal query: " . ($normalQuery ? "Yes" : "No") . "\n";

// Try to retrieve with withTrashed
$trashedQuery = Company::withTrashed()->find($company->id);
echo "Can find with withTrashed: " . ($trashedQuery ? "Yes (Has deleted_at: " . 
    ($trashedQuery->deleted_at ? "Yes" : "No") . ")" : "No") . "\n";

// Restore the company
$trashedQuery->restore();
echo "Company restored\n";

// Check if it's back in normal queries
$restoredQuery = Company::find($company->id);
echo "Can find after restore: " . ($restoredQuery ? "Yes" : "No") . "\n";

// Clean up
$restoredQuery->forceDelete();
echo "Company permanently deleted\n";

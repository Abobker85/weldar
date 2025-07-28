<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Welder;
use App\Models\QualificationTest;
use Illuminate\Support\Facades\Hash;

class TestQualificationSeeder extends Seeder
{
    /**
     * Run the database seeds to create test qualification data.
     */
    public function run(): void
    {
        // Create a test admin user if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_admin' => 1,
            ]
        );        // Create a test company
        $company = Company::firstOrCreate(
            ['name' => 'Test Company'],
            [
                'code' => 'TEST-01', // Adding required code field
                'address' => '123 Test Street, Industrial Area',
                'phone' => '+966 500000000',
                'email' => 'info@testcompany.com',
                'created_by' => $user->id,
            ]
        );        // Create a test welder
        $welder = Welder::firstOrCreate(
            ['passport_id_no' => 'A1234567'],
            [
                'name' => 'John Smith',
                'company_id' => $company->id,
                'nationality' => 'American',
                'welder_no' => 'WLD-123',
                'rt_report' => 'RT-123456', // Required by migration
                'rt_report_serial' => 'RS-123456', // Required by schema
                'created_by' => $user->id,
            ]
        );

        // Create a qualification test with joint diagram data
        QualificationTest::firstOrCreate(
            ['cert_no' => 'QT-2025-001'],
            [
                'welder_no' => $welder->id,
                'test_date' => now(),
                'wps_no' => 'WPS-001',
                'welding_process' => 'GTAW/SMAW',
                'test_coupon' => 'Pipe',
                'welding_positions' => '6G',
                'qualified_position' => 'All Positions',
                'dia_inch' => '6 inch',
                'coupon_thickness_mm' => '14.27',
                'deposit_thickness' => '14.27',
                'coupon_material' => 'Carbon Steel',
                'qualified_material' => 'P-No.1 to P-No.1',
                'qualified_thickness_range' => '5mm - 30mm',
                'qualified_dia_inch' => '≥ 73mm',
                'backing' => 'Yes',
                'qualified_backing' => 'With or Without',
                'filler_metal_f_no' => 'F-No.6 / F-No.4',
                'aws_spec_no' => 'A5.18 / A5.1',
                'filler_metal_classif' => 'ER70S-2 / E7018-1',
                'qualification_code' => 'ASME Section IX',
                'is_active' => true,
                'vt_date' => now(),
                'vt_report_no' => 'VT-2025-001',
                'vt_result' => 'ACC',
                'rt_date' => now(),
                'rt_report_no' => 'RT-2025-001',
                'rt_result' => 'ACC',
                'test_result' => true,
                'electric_char' => 'DCEN/DCEP',
                'qualified_ec' => 'DCEN/DCEP',
                'remarks' => 'This is a test qualification',
                'created_by' => $user->id,
                
                // Joint diagram details
                'joint_type' => 'Single V-Groove',
                'joint_description' => 'Butt Joint (Pipe)',
                'joint_angle' => '30°',
                'joint_total_angle' => '60°',
                'root_gap' => '2-3mm',
                'root_face' => '1-2mm',
                'pipe_outer_diameter' => '168.28 mm (6 Inch Sch.80)',
                'base_metal_p_no' => 'P-No.1 Gr.1 to P-No.1 Gr.1',
                'filler_metal_form' => 'Solid Wire / Coated Electrode',
                'inert_gas_backing' => 'Not Used',
                'gtaw_thickness' => 'Approx. 4 mm',
                'smaw_thickness' => 'Approx. 10.27 mm',
                'vertical_progression' => 'Uphill',
            ]
        );
    }
}

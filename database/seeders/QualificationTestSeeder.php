<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Welder;
use App\Models\Company;
use App\Models\QualificationTest;
use Carbon\Carbon;

class QualificationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default company for the welders
        $company = Company::firstOrCreate([
            'name' => 'AIC Company',
        ], [
            'address' => 'ASFAN',
            'phone' => '123456789',
            'email' => 'info@aic-company.com',
        ]);

        // Sample data from Excel
        $welderData = [
            [
                'name' => 'BIRENDRA KUMAR',
                'passport_id_no' => 'V5106562',
                'welder_no' => 'W-0013',
                'location' => 'ASFAN',
                'qualification' => [
                    'sr_no' => '1',
                    'work_order_no' => '1',
                    'location' => 'ASFAN',
                    'wps_no' => 'AIC-WPS-PGM-045',
                    'welding_process' => 'GTAW (Root/Hot) SMAW(Filling/Cap)',
                    'test_coupon' => 'PIPE',
                    'dia_inch' => '3 inch',
                    'qualified_dia_inch' => '2 7/8 inch to Unlimited',
                    'coupon_material' => 'ASTM A312 TP316L',
                    'qualified_material' => 'P1 through P15F, P34 and P41 through P49',
                    'coupon_thickness_mm' => 15.24,
                    'deposit_thickness' => '',
                    'qualified_thickness_range' => 'GTAW - 8mm Max; SMAW - 22.48mm Max',
                    'welding_positions' => '6G',
                    'qualified_position' => 'All [Groove & Fillet]',
                    'filler_metal_f_no' => 'F6 & F5',
                    'aws_spec_no' => 'ER 316L + E316L-17',
                    'filler_metal_classif' => '5.9 + 5.4',
                    'backing' => 'GTAW - Without, SMAW - With',
                    'qualified_backing' => 'GTAW - With or Without, SMAW - With',
                    'electric_char' => 'GTAW·DCEN, SMAW- DCEP',
                    'qualified_ec' => 'GTAW-DCEN, SMAW-DCEN/DCEP/AC',
                    'vt_date' => '2023-04-01',
                    'vt_report_no' => 'EEA-AIC-VT-WQT-0001',
                    'vt_result' => 'ACC',
                    'rt_date' => '2023-04-02',
                    'rt_report_no' => 'EEA-AIC-RT-0001',
                    'rt_result' => 'ACC',
                    'cert_no' => 'EEA-AIC-WQT-0001',
                    'qualification_code' => 'ASME SEC IX - 2021',
                    'remarks' => 'ASFAN',
                ]
            ],
            [
                'name' => 'SUMESH MAVILA',
                'passport_id_no' => 'P1280321',
                'welder_no' => 'W-0062',
                'location' => 'ASFAN',
                'qualification' => [
                    'sr_no' => '2',
                    'work_order_no' => '1',
                    'location' => 'ASFAN',
                    'wps_no' => 'AIC-WPS-PGM-045',
                    'welding_process' => 'GTAW (Root/Hot) SMAW(Filling/Cap)',
                    'test_coupon' => 'PIPE',
                    'dia_inch' => '3 inch',
                    'qualified_dia_inch' => '2 7/8 inch to Unlimited',
                    'coupon_material' => 'ASTM A312 TP316L',
                    'qualified_material' => 'P1 through P15F, P34 and P41 through P49',
                    'coupon_thickness_mm' => 15.24,
                    'deposit_thickness' => '',
                    'qualified_thickness_range' => 'GTAW - 8mm Max; SMAW - 22.48mm Max',
                    'welding_positions' => '6G',
                    'qualified_position' => 'All [Groove & Fillet]',
                    'filler_metal_f_no' => 'F6 & F5',
                    'aws_spec_no' => 'ER 316L + E316L-17',
                    'filler_metal_classif' => '5.9 + 5.4',
                    'backing' => 'GTAW - Without, SMAW - With',
                    'qualified_backing' => 'GTAW - With or Without, SMAW - With',
                    'electric_char' => 'GTAW·DCEN, SMAW- DCEP',
                    'qualified_ec' => 'GTAW-DCEN, SMAW-DCEN/DCEP/AC',
                    'vt_date' => '2023-03-25',
                    'vt_report_no' => 'EEA-AIC-VT-WQT-0002',
                    'vt_result' => 'ACC',
                    'rt_date' => '2023-03-26',
                    'rt_report_no' => 'EEA-AIC-RT-0002',
                    'rt_result' => 'ACC',
                    'cert_no' => 'EEA-AIC-WQT-0002',
                    'qualification_code' => 'ASME SEC IX - 2021',
                    'remarks' => 'ASFAN',
                ]
            ]
        ];

        foreach ($welderData as $data) {
            // Create or update welder
            $welder = Welder::firstOrCreate(
                ['passport_id_no' => $data['passport_id_no']],
                [
                    'name' => $data['name'],
                    'welder_no' => $data['welder_no'],
                    'company_id' => $company->id,
                    'location' => $data['location'],
                ]
            );

            // Create qualification test
            $qualification = $data['qualification'];
            $qualification['welder_no'] = $welder->id;
              // Set test_result based on VT and RT results
            $qualification['test_result'] = ($qualification['vt_result'] === 'ACC' && $qualification['rt_result'] === 'ACC');
            
            QualificationTest::firstOrCreate(
                ['cert_no' => $qualification['cert_no']],
                $qualification
            );
        }
    }
}

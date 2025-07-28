<?php

namespace App\Enums;

class CertificateOptions
{
    public static function weldingProcesses()
    {
        return [
            'SMAW' => 'SMAW (Shielded Metal Arc Welding)',
        ];
    }
    
    public static function testPositions()
    {
        return [
            '1G' => '1G (Flat Position)',
            '2G' => '2G (Horizontal Position)',
            '3G' => '3G (Vertical Position)',
            '4G' => '4G (Overhead Position)',
            '5G' => '5G (Horizontal Fixed Position - Pipe)',
            '6G' => '6G (Inclined Position - Pipe)',
        ];
    }
    
    public static function verticalProgressions()
    {
        return [
            'Uphill' => 'Uphill',
            'Downhill' => 'Downhill',
        ];
    }
    
    public static function pipeDiameterTypes()
    {
        return [
            '8_nps' => '8" NPS (219.1 mm)',
            '6_nps' => '6" NPS (168.3 mm)',
            '4_nps' => '4" NPS (114.3 mm)',
            '2_nps' => '2" NPS (60.3 mm)',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function baseMetalPNumbers()
    {
        return [
            'P NO.1 TO P NO.1' => 'P NO.1 TO P NO.1',
            'P NO.3 TO P NO.3' => 'P NO.3 TO P NO.3',
            'P NO.4 TO P NO.4' => 'P NO.4 TO P NO.4',
            'P NO.5A TO P NO.5A' => 'P NO.5A TO P NO.5A',
            'P NO.8 TO P NO.8' => 'P NO.8 TO P NO.8',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function fillerFNumbers()
    {
        return [
            'F4_with_backing' => 'F-No.4 With Backing',
            'F5_with_backing' => 'F-No.5 With Backing',
            'F4_without_backing' => 'F-No.4 Without Backing',
            'F5_without_backing' => 'F-No.5 Without Backing',
            'F43' => 'F-No. 43',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function fillerSpecs()
    {
        return [
            '5.1' => 'AWS A5.1',
            '5.4' => 'AWS A5.4',
            '5.5' => 'AWS A5.5',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function fillerClasses()
    {
        return [
            'E7018-1' => 'E7018-1',
            'E7018' => 'E7018',
            'E7016' => 'E7016',
            'E308L-16' => 'E308L-16',
            'E309L-16' => 'E309L-16',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function backingTypes()
    {
        return [
            'With Backing' => 'With Backing',
            'Without Backing' => 'Without Backing',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function testResults()
    {
        return [
            '1' => 'Pass',
            '0' => 'Fail',
        ];
    }
}

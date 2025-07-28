<?php

namespace App\Enums;

class QualificationOptions
{
    public static function weldingProcesses()
    {
        return [
            'SMAW' => 'SMAW (Shielded Metal Arc Welding)',
            'GMAW' => 'GMAW (Gas Metal Arc Welding)',
            'GTAW' => 'GTAW (Gas Tungsten Arc Welding)',
            'FCAW' => 'FCAW (Flux Cored Arc Welding)',
            'GTAW + SMAW' => 'GTAW + SMAW (Combined Process)',
            'SAW' => 'SAW (Submerged Arc Welding)',
            '__manual__' => '-- Manual Entry --',
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
            '1G, 2G, 5G, 6G' => '1G, 2G, 5G, 6G (All Positions - Pipe)',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function certificationCodes()
    {
        return [
            'ASME IX' => 'ASME IX',
            'AWS D1.1' => 'AWS D1.1',
            'API 1104' => 'API 1104',
            'EN ISO 9606-1' => 'EN ISO 9606-1',
            'EN 13067' => 'EN 13067',
            'BS 4872-1' => 'BS 4872-1',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function couponMaterials()
    {
        return [
            'Carbon Steel' => 'Carbon Steel',
            'Stainless Steel' => 'Stainless Steel',
            'Aluminum' => 'Aluminum',
            'Carbon Steel to Stainless Steel' => 'Carbon Steel to Stainless Steel (Dissimilar)',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function qualifiedMaterials()
    {
        return [
            'Carbon Steel' => 'Carbon Steel',
            'Stainless Steel' => 'Stainless Steel',
            'P-No.1 to P-No.1' => 'P-No.1 to P-No.1',
            'P-No.1 to P-No.8' => 'P-No.1 to P-No.8 (Dissimilar)',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function qualifiedThicknessRanges()
    {
        return [
            '3mm to 2t' => '3mm to 2t',
            '5mm to 2t' => '5mm to 2t',
            't to 2t' => 't to 2t',
            '1.5t to 2t' => '1.5t to 2t',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function electricCharacteristics()
    {
        return [
            'DC+' => 'DC+ (DCEP)',
            'DC-' => 'DC- (DCEN)',
            'AC' => 'AC',
            'DC+ & DC-' => 'DC+ & DC-',
            '__manual__' => '-- Manual Entry --',
        ];
    }
    
    public static function testResults()
    {
        return [
            'ACC' => 'Acceptable',
            'REJ' => 'Rejected',
            'N/A' => 'Not Applicable',
        ];
    }
    
    public static function nationalities()
    {
        return [
            'Afghan' => 'Afghan',
            'Albanian' => 'Albanian',
            'Algerian' => 'Algerian',
            'American' => 'American',
            'Andorran' => 'Andorran',
            'Angolan' => 'Angolan',
            'Antiguan' => 'Antiguan',
            'Argentine' => 'Argentine',
            'Armenian' => 'Armenian',
            'Australian' => 'Australian',
            'Austrian' => 'Austrian',
            'Azerbaijani' => 'Azerbaijani',
            'Bahamian' => 'Bahamian',
            'Bahraini' => 'Bahraini',
            'Bangladeshi' => 'Bangladeshi',
            'Barbadian' => 'Barbadian',
            'Belarusian' => 'Belarusian',
            'Belgian' => 'Belgian',
            'Belizean' => 'Belizean',
            'Beninese' => 'Beninese',
            'Bhutanese' => 'Bhutanese',
            'Bolivian' => 'Bolivian',
            'Bosnian' => 'Bosnian',
            'Brazilian' => 'Brazilian',
            'British' => 'British',
            'Bruneian' => 'Bruneian',
            'Bulgarian' => 'Bulgarian',
            'Burkinabe' => 'Burkinabe',
            'Burmese' => 'Burmese',
            'Burundian' => 'Burundian',
            'Cambodian' => 'Cambodian',
            'Cameroonian' => 'Cameroonian',
            'Canadian' => 'Canadian',
            'Cape Verdean' => 'Cape Verdean',
            'Central African' => 'Central African',
            'Chadian' => 'Chadian',
            'Chilean' => 'Chilean',
            'Chinese' => 'Chinese',
            'Colombian' => 'Colombian',
            'Comoran' => 'Comoran',
            'Congolese' => 'Congolese',
            'Costa Rican' => 'Costa Rican',
            'Croatian' => 'Croatian',
            'Cuban' => 'Cuban',
            'Cypriot' => 'Cypriot',
            'Czech' => 'Czech',
            'Danish' => 'Danish',
            'Djiboutian' => 'Djiboutian',
            'Dominican' => 'Dominican',
            'Dutch' => 'Dutch',
            'East Timorese' => 'East Timorese',
            'Ecuadorian' => 'Ecuadorian',
            'Egyptian' => 'Egyptian',
            'Emirian' => 'Emirian',
            'Equatorial Guinean' => 'Equatorial Guinean',
            'Eritrean' => 'Eritrean',
            'Estonian' => 'Estonian',
            'Ethiopian' => 'Ethiopian',
            'Fijian' => 'Fijian',
            'Filipino' => 'Filipino',
            'Finnish' => 'Finnish',
            'French' => 'French',
            'Gabonese' => 'Gabonese',
            'Gambian' => 'Gambian',
            'Georgian' => 'Georgian',
            'German' => 'German',
            'Ghanaian' => 'Ghanaian',
            'Greek' => 'Greek',
            'Grenadian' => 'Grenadian',
            'Guatemalan' => 'Guatemalan',
            'Guinea-Bissauan' => 'Guinea-Bissauan',
            'Guinean' => 'Guinean',
            'Guyanese' => 'Guyanese',
            'Haitian' => 'Haitian',
            'Honduran' => 'Honduran',
            'Hungarian' => 'Hungarian',
            'Icelandic' => 'Icelandic',
            'Indian' => 'Indian',
            'Indonesian' => 'Indonesian',
            'Iranian' => 'Iranian',
            'Iraqi' => 'Iraqi',
            'Irish' => 'Irish',
            'Israeli' => 'Israeli',
            'Italian' => 'Italian',
            'Ivorian' => 'Ivorian',
            'Jamaican' => 'Jamaican',
            'Japanese' => 'Japanese',
            'Jordanian' => 'Jordanian',
            'Kazakhstani' => 'Kazakhstani',
            'Kenyan' => 'Kenyan',
            'Kittian and Nevisian' => 'Kittian and Nevisian',
            'Kuwaiti' => 'Kuwaiti',
            'Kyrgyz' => 'Kyrgyz',
            'Laotian' => 'Laotian',
            'Latvian' => 'Latvian',
            'Lebanese' => 'Lebanese',
            'Liberian' => 'Liberian',
            'Libyan' => 'Libyan',
            'Liechtensteiner' => 'Liechtensteiner',
            'Lithuanian' => 'Lithuanian',
            'Luxembourger' => 'Luxembourger',
            'Macedonian' => 'Macedonian',
            'Malagasy' => 'Malagasy',
            'Malawian' => 'Malawian',
            'Malaysian' => 'Malaysian',
            'Maldivian' => 'Maldivian',
            'Malian' => 'Malian',
            'Maltese' => 'Maltese',
            'Marshallese' => 'Marshallese',
            'Mauritanian' => 'Mauritanian',
            'Mauritian' => 'Mauritian',
            'Mexican' => 'Mexican',
            'Micronesian' => 'Micronesian',
            'Moldovan' => 'Moldovan',
            'Monacan' => 'Monacan',
            'Mongolian' => 'Mongolian',
            'Moroccan' => 'Moroccan',
            'Mosotho' => 'Mosotho',
            'Motswana' => 'Motswana',
            'Mozambican' => 'Mozambican',
            'Namibian' => 'Namibian',
            'Nauruan' => 'Nauruan',
            'Nepalese' => 'Nepalese',
            'New Zealander' => 'New Zealander',
            'Nicaraguan' => 'Nicaraguan',
            'Nigerian' => 'Nigerian',
            'Nigerien' => 'Nigerien',
            'North Korean' => 'North Korean',
            'Norwegian' => 'Norwegian',
            'Omani' => 'Omani',
            'Pakistani' => 'Pakistani',
            'Palauan' => 'Palauan',
            'Palestinian' => 'Palestinian',
            'Panamanian' => 'Panamanian',
            'Papua New Guinean' => 'Papua New Guinean',
            'Paraguayan' => 'Paraguayan',
            'Peruvian' => 'Peruvian',
            'Polish' => 'Polish',
            'Portuguese' => 'Portuguese',
            'Qatari' => 'Qatari',
            'Romanian' => 'Romanian',
            'Russian' => 'Russian',
            'Rwandan' => 'Rwandan',
            'Saint Lucian' => 'Saint Lucian',
            'Salvadoran' => 'Salvadoran',
            'Samoan' => 'Samoan',
            'San Marinese' => 'San Marinese',
            'Sao Tomean' => 'Sao Tomean',
            'Saudi' => 'Saudi',
            'Senegalese' => 'Senegalese',
            'Serbian' => 'Serbian',
            'Seychellois' => 'Seychellois',
            'Sierra Leonean' => 'Sierra Leonean',
            'Singaporean' => 'Singaporean',
            'Slovakian' => 'Slovakian',
            'Slovenian' => 'Slovenian',
            'Solomon Islander' => 'Solomon Islander',
            'Somali' => 'Somali',
            'South African' => 'South African',
            'South Korean' => 'South Korean',
            'Spanish' => 'Spanish',
            'Sri Lankan' => 'Sri Lankan',
            'Sudanese' => 'Sudanese',
            'Surinamer' => 'Surinamer',
            'Swazi' => 'Swazi',
            'Swedish' => 'Swedish',
            'Swiss' => 'Swiss',
            'Syrian' => 'Syrian',
            'Taiwanese' => 'Taiwanese',
            'Tajik' => 'Tajik',
            'Tanzanian' => 'Tanzanian',
            'Thai' => 'Thai',
            'Togolese' => 'Togolese',
            'Tongan' => 'Tongan',
            'Trinidadian or Tobagonian' => 'Trinidadian or Tobagonian',
            'Tunisian' => 'Tunisian',
            'Turkish' => 'Turkish',
            'Tuvaluan' => 'Tuvaluan',
            'Ugandan' => 'Ugandan',
            'Ukrainian' => 'Ukrainian',
            'Uruguayan' => 'Uruguayan',
            'Uzbekistani' => 'Uzbekistani',
            'Venezuelan' => 'Venezuelan',
            'Vietnamese' => 'Vietnamese',
            'Welsh' => 'Welsh',
            'Yemenite' => 'Yemenite',
            'Zambian' => 'Zambian',
            'Zimbabwean' => 'Zimbabwean',
        ];
    }
    
    /**
     * Generate HTML for a select field with manual entry option
     *
     * @param string $name Field name
     * @param array $options Select options
     * @param string|null $selected Selected value
     * @param bool $required Whether field is required
     * @return string Generated HTML
     */
    public static function selectWithManualEntry($name, $options, $selected = null, $required = false)
    {
        // Check if "__manual__" is selected or if the selected value doesn't exist in options
        $isManualSelected = $selected === '__manual__';
        $manualValue = '';
        
        if (!$isManualSelected && $selected && !array_key_exists($selected, $options)) {
            $isManualSelected = true;
            $manualValue = $selected;
        }
        
        $html = '<div class="input-group">';
        
        // Main select element
        $html .= '<select class="form-select" id="' . $name . '" name="' . $name . '" ' 
              . ($required ? 'required' : '') . ' data-has-manual="true">';
        $html .= '<option value="">-- Select Option --</option>';
        
        foreach ($options as $value => $label) {
            $isSelected = $selected === $value || ($isManualSelected && $value === '__manual__') ? 'selected' : '';
            $html .= '<option value="' . $value . '" ' . $isSelected . '>' . $label . '</option>';
        }
        
        $html .= '</select>';
        $html .= '</div>';
        
        // Manual input field (hidden by default, shown when "__manual__" is selected)
        $manualDisplay = $isManualSelected ? 'block' : 'none';
        $html .= '<div id="' . $name . '_manual_container" style="display:' . $manualDisplay . '; margin-top:8px">';
        $html .= '<input type="text" class="form-control" id="' . $name . '_manual" name="' . $name . '_manual" ';
        $html .= 'placeholder="Enter custom value" value="' . $manualValue . '">';
        $html .= '</div>';
        
        return $html;
    }
}

<?php 
class Geography extends Object {
	
	// App::import('Lib', 'Shop.Geography');
	
	var $regions = array(
		'CA' => array(
			'AB' => 'Alberta',
			'BC' => 'British Columbia',
			'MB' => 'Manitoba',
			'NB' => 'New Brunswick',
			'NL' => 'Newfoundland and Labrador',
			'NS' => 'Nova Scotia',
			'NT' => 'Northwest Territories',
			'NU' => 'Nunavut',
			'ON' => 'Ontario',
			'PE' => 'Prince Edward Island',
			'QC' => 'Quebec',
			'SK' => 'Saskatchewan',
			'YT' => 'Yukon'
		),
		'US' => array(
			'AL' => 'Alabama',
			'AK' => 'Alaska',
			'AZ' => 'Arizona',
			'AR' => 'Arkansas',
			'CA' => 'California',
			'CO' => 'Colorado',
			'CT' => 'Connecticut',
			'DE' => 'Delaware',
			'FL' => 'Florida',
			'GA' => 'Georgia',
			'HI' => 'Hawaii',
			'ID' => 'Idaho',
			'IL' => 'Illinois',
			'IN' => 'Indiana',
			'IA' => 'Iowa',
			'KS' => 'Kansas',
			'KY' => 'Kentucky',
			'LA' => 'Louisiana',
			'ME' => 'Maine',
			'MD' => 'Maryland',
			'MA' => 'Massachusetts',
			'MI' => 'Michigan',
			'MN' => 'Minnesota',
			'MS' => 'Mississippi',
			'MO' => 'Missouri',
			'MT' => 'Montana',
			'NE' => 'Nebraska',
			'NV' => 'Nevada',
			'NH' => 'New Hampshire',
			'NJ' => 'New Jersey',
			'NM' => 'New Mexico',
			'NY' => 'New York',
			'NC' => 'North Carolina',
			'ND' => 'North Dakota',
			'OH' => 'Ohio',
			'OK' => 'Oklahoma',
			'OR' => 'Oregon',
			'PA' => 'Pennsylvania',
			'RI' => 'Rhode Island',
			'SC' => 'South Carolina',
			'SD' => 'South Dakota',
			'TN' => 'Tennessee',
			'TX' => 'Texas',
			'UT' => 'Utah',
			'VT' => 'Vermont',
			'VA' => 'Virginia',
			'WA' => 'Washington',
			'WV' => 'West Virginia',
			'WI' => 'Wisconsin',
			'WY' => 'Wyoming'
		)
	);
		
	var $localeTerms = array(
		'region' => array(
			'CA' => 'Province',
			'US' => 'State',
		),
	)
	
	var $countries = array(
		'AF' => 'Afghanistan',
		'AX' => 'Åland Islands',
		'AL' => 'Albania',
		'DZ' => 'Algeria',
		'AS' => 'American Samoa',
		'AD' => 'Andorra',
		'AO' => 'Angola',
		'AI' => 'Anguilla',
		'AQ' => 'Antarctica',
		'AG' => 'Antigua and Barbuda',
		'AR' => 'Argentina',
		'AM' => 'Armenia',
		'AW' => 'Aruba',
		'AU' => 'Australia',
		'AT' => 'Austria',
		'AZ' => 'Azerbaijan',
		'BS' => 'Bahamas',
		'BH' => 'Bahrain',
		'BD' => 'Bangladesh',
		'BB' => 'Barbados',
		'BY' => 'Belarus',
		'BE' => 'Belgium',
		'BZ' => 'Belize',
		'BJ' => 'Benin',
		'BM' => 'Bermuda',
		'BT' => 'Bhutan',
		'BO' => 'Bolivia',
		'BA' => 'Bosnia and Herzegovina',
		'BW' => 'Botswana',
		'BV' => 'Bouvet Island',
		'BR' => 'Brazil',
		'IO' => 'British Indian Ocean Territory',
		'BN' => 'Brunei Darussalam',
		'BG' => 'Bulgaria',
		'BF' => 'Burkina Faso',
		'BI' => 'Burundi',
		'KH' => 'Cambodia',
		'CM' => 'Cameroon',
		'CA' => 'Canada',
		'CV' => 'Cape Verde',
		'KY' => 'Cayman Islands',
		'CF' => 'Central African Republic',
		'TD' => 'Chad',
		'CL' => 'Chile',
		'CN' => 'China',
		'CX' => 'Christmas Island',
		'CC' => 'Cocos (Keeling) Island',
		'CO' => 'Colombia',
		'KM' => 'Comoros',
		'CG' => 'Congo',
		'CD' => 'Congo, The Democratic Republic of the',
		'CK' => 'Cook Islands',
		'CR' => 'Costa Rica',
		'CI' => 'Côte d\'Ivoire',
		'HR' => 'Croatia',
		'CU' => 'Cuba',
		'CY' => 'Cyprus',
		'CZ' => 'Czech Republic',
		'DK' => 'Denmark',
		'DJ' => 'Djibouti',
		'DM' => 'Dominica',
		'DO' => 'Dominican Republic',
		'EC' => 'Ecuador',
		'EG' => 'Egypt',
		'SV' => 'El Salvador',
		'GQ' => 'Equatorial Guinea',
		'ER' => 'Eritrea',
		'EE' => 'Estonia',
		'ET' => 'Ethiopia',
		'FO' => 'Faeroe Islands',
		'FK' => 'Falkland Islands (Malvinas)',
		'FJ' => 'Fiji',
		'FI' => 'Finland',
		'FR' => 'France',
		'GF' => 'French Guiana',
		'PF' => 'French Polynesia',
		'TF' => 'French Southern Territories',
		'GA' => 'Gabon',
		'GM' => 'Gambia',
		'GE' => 'Georgia',
		'DE' => 'Germany',
		'GH' => 'Ghana',
		'GI' => 'Gibraltar',
		'GR' => 'Greece',
		'GL' => 'Greenland',
		'GD' => 'Grenada',
		'GP' => 'Guadeloupe',
		'GU' => 'Guam',
		'GT' => 'Guatemala',
		'GG' => 'Guernsey',
		'GN' => 'Guinea',
		'GW' => 'Guinea-Bissau',
		'GY' => 'Guyana',
		'HT' => 'Haiti',
		'HM' => 'Heard Island and McDonald Islands',
		'HN' => 'Honduras',
		'HK' => 'Hong Kong',
		'HU' => 'Hungary',
		'IS' => 'Iceland',
		'IN' => 'India',
		'ID' => 'Indonesia',
		'IR' => 'Iran',
		'IQ' => 'Iraq',
		'IE' => 'Ireland, Republic of (EIRE)',
		'IM' => 'Isle of Man',
		'IL' => 'Israel',
		'IT' => 'Italy',
		'JM' => 'Jamaica',
		'JP' => 'Japan',
		'JE' => 'Jersey',
		'JO' => 'Jordan',
		'KZ' => 'Kazakhstan',
		'KE' => 'Kenya',
		'KI' => 'Kiribati',
		'KP' => 'Korea, North',
		'KR' => 'Korea, South',
		'KW' => 'Kuwait',
		'KG' => 'Kyrgyzstan',
		'LA' => 'Laos',
		'LV' => 'Latvia',
		'LB' => 'Lebanon',
		'LS' => 'Lesotho',
		'LR' => 'Liberia',
		'LY' => 'Libya',
		'LI' => 'Liechtenstein',
		'LT' => 'Lithuania',
		'LU' => 'Luxembourg',
		'MO' => 'Macao',
		'MK' => 'Macedonia (FYROM)',
		'MG' => 'Madagascar',
		'MW' => 'Malawi',
		'MY' => 'Malaysia',
		'MV' => 'Maldives',
		'ML' => 'Mali',
		'MT' => 'Malta',
		'MH' => 'Marshall Islands',
		'MQ' => 'Martinique',
		'MR' => 'Mauritania',
		'MU' => 'Mauritius',
		'YT' => 'Mayotte',
		'MX' => 'Mexico',
		'FM' => 'Micronesia, Federated States of',
		'MD' => 'Moldova',
		'MC' => 'Monaco',
		'MN' => 'Mongolia',
		'ME' => 'Montenegro',
		'MS' => 'Montserrat',
		'MA' => 'Morocco',
		'MZ' => 'Mozambique',
		'MM' => 'Myanmar',
		'NA' => 'Namibia',
		'NR' => 'Nauru',
		'NP' => 'Nepal',
		'NL' => 'Netherlands',
		'AN' => 'Netherlands Antilles',
		'NC' => 'New Caledonia',
		'NZ' => 'New Zealand',
		'NI' => 'Nicaragua',
		'NE' => 'Niger',
		'NG' => 'Nigeria',
		'NU' => 'Niue',
		'NF' => 'Norfolk Island',
		'MP' => 'Northern Mariana Islands',
		'NO' => 'Norway',
		'OM' => 'Oman',
		'PK' => 'Pakistan',
		'PW' => 'Palau',
		'PS' => 'Palestinian Territory, Occupied',
		'PA' => 'Panama',
		'PG' => 'Papua New Guinea',
		'PY' => 'Paraguay',
		'PE' => 'Peru',
		'PH' => 'Philippines',
		'PN' => 'Pitcairn',
		'PL' => 'Poland',
		'PT' => 'Portugal',
		'PR' => 'Puerto Rico',
		'QA' => 'Qatar',
		'RE' => 'Réunion',
		'RO' => 'Romania',
		'RU' => 'Russian Federation',
		'RW' => 'Rwanda',
		'BL' => 'Saint Barthélemy',
		'SH' => 'Saint Helena',
		'KN' => 'Saint Kitts and Nevis',
		'LC' => 'Saint Lucia',
		'MF' => 'Saint Martin (French part)',
		'PM' => 'Saint Pierre and Miquelon',
		'VC' => 'Saint Vincent and the Grenadines',
		'WS' => 'Samoa',
		'SM' => 'San Marino',
		'ST' => 'Sao Tome and Principe',
		'SA' => 'Saudi Arabia',
		'SN' => 'Senegal',
		'RS' => 'Serbia',
		'SC' => 'Seychelles',
		'SL' => 'Sierra Leone',
		'SG' => 'Singapore',
		'SK' => 'Slovakia',
		'SI' => 'Slovenia',
		'SB' => 'Solomon Islands',
		'SO' => 'Somalia',
		'ZA' => 'South Africa',
		'GS' => 'South Georgia and the South Sandwich Islands',
		'ES' => 'Spain',
		'LK' => 'Sri Lanka',
		'SD' => 'Sudan',
		'SR' => 'Suriname',
		'SZ' => 'Swaziland',
		'SE' => 'Sweden',
		'CH' => 'Switzerland',
		'SY' => 'Syria',
		'TW' => 'Taiwan',
		'TJ' => 'Tajikistan',
		'TZ' => 'Tanzania, United Republic of',
		'TH' => 'Thailand',
		'TL' => 'Timor-Leste',
		'TG' => 'Togo',
		'TK' => 'Tokelau',
		'TO' => 'Tonga',
		'TT' => 'Trinidad and Tobago',
		'TN' => 'Tunisia',
		'TR' => 'Turkey',
		'TM' => 'Turkmenistan',
		'TC' => 'Turks and Caicos Islands',
		'TV' => 'Tuvalu',
		'UG' => 'Uganda',
		'UA' => 'Ukraine',
		'AE' => 'United Arab Emirates',
		'GB' => 'United Kingdom',
		'US' => 'United States',
		'UM' => 'United States Minor Outlying Islands',
		'UY' => 'Uruguay',
		'UZ' => 'Uzbekistan',
		'VU' => 'Vanuatu',
		'VA' => 'Vatican City State',
		'VE' => 'Venezuela',
		'VN' => 'Viet Nam',
		'VG' => 'Virgin Islands, British',
		'VI' => 'Virgin Islands, U.S.',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe'
	);
	
	
	var $threeLetterCodes = array(
		'AF' => 'AFG',
		'AL' => 'ALB',
		'DZ' => 'DZA',
		'AS' => 'ASM',
		'AD' => 'AND',
		'AO' => 'AGO',
		'AI' => 'AIA',
		'AQ' => 'ATA',
		'AG' => 'ATG',
		'AR' => 'ARG',
		'AM' => 'ARM',
		'AW' => 'ABW',
		'AU' => 'AUS',
		'AT' => 'AUT',
		'AZ' => 'AZE',
		'BS' => 'BHS',
		'BH' => 'BHR',
		'BD' => 'BGD',
		'BB' => 'BRB',
		'BY' => 'BLR',
		'BE' => 'BEL',
		'BZ' => 'BLZ',
		'BJ' => 'BEN',
		'BM' => 'BMU',
		'BT' => 'BTN',
		'BO' => 'BOL',
		'BA' => 'BIH',
		'BW' => 'BWA',
		'BR' => 'BRA',
		'IO' => 'IOT',
		'VG' => 'VGB',
		'BN' => 'BRN',
		'BG' => 'BGR',
		'BF' => 'BFA',
		'MM' => 'MMR',
		'BI' => 'BDI',
		'KH' => 'KHM',
		'CM' => 'CMR',
		'CA' => 'CAN',
		'CV' => 'CPV',
		'KY' => 'CYM',
		'CF' => 'CAF',
		'TD' => 'TCD',
		'CL' => 'CHL',
		'CN' => 'CHN',
		'CX' => 'CXR',
		'CC' => 'CCK',
		'CO' => 'COL',
		'KM' => 'COM',
		'CK' => 'COK',
		'CR' => 'CRC',
		'HR' => 'HRV',
		'CU' => 'CUB',
		'CY' => 'CYP',
		'CZ' => 'CZE',
		'CD' => 'COD',
		'DK' => 'DNK',
		'DJ' => 'DJI',
		'DM' => 'DMA',
		'DO' => 'DOM',
		'EC' => 'ECU',
		'EG' => 'EGY',
		'SV' => 'SLV',
		'GQ' => 'GNQ',
		'ER' => 'ERI',
		'EE' => 'EST',
		'ET' => 'ETH',
		'FK' => 'FLK',
		'FO' => 'FRO',
		'FJ' => 'FJI',
		'FI' => 'FIN',
		'FR' => 'FRA',
		'PF' => 'PYF',
		'GA' => 'GAB',
		'GM' => 'GMB',
		'GE' => 'GEO',
		'DE' => 'DEU',
		'GH' => 'GHA',
		'GI' => 'GIB',
		'GR' => 'GRC',
		'GL' => 'GRL',
		'GD' => 'GRD',
		'GU' => 'GUM',
		'GT' => 'GTM',
		'GN' => 'GIN',
		'GW' => 'GNB',
		'GY' => 'GUY',
		'HT' => 'HTI',
		'VA' => 'VAT',
		'HN' => 'HND',
		'HK' => 'HKG',
		'HU' => 'HUN',
		'IN' => 'IND',
		'ID' => 'IDN',
		'IR' => 'IRN',
		'IQ' => 'IRQ',
		'IE' => 'IRL',
		'IM' => 'IMN',
		'IL' => 'ISR',
		'IT' => 'ITA',
		'CI' => 'CIV',
		'JM' => 'JAM',
		'JP' => 'JPN',
		'JE' => 'JEY',
		'JO' => 'JOR',
		'KZ' => 'KAZ',
		'KE' => 'KEN',
		'KI' => 'KIR',
		'KW' => 'KWT',
		'KG' => 'KGZ',
		'LA' => 'LAO',
		'LV' => 'LVA',
		'LB' => 'LBN',
		'LS' => 'LSO',
		'LR' => 'LBR',
		'LY' => 'LBY',
		'LI' => 'LIE',
		'LT' => 'LTU',
		'LU' => 'LUX',
		'MO' => 'MAC',
		'MK' => 'MKD',
		'MG' => 'MDG',
		'MW' => 'MWI',
		'MY' => 'MYS',
		'MV' => 'MDV',
		'ML' => 'MLI',
		'MT' => 'MLT',
		'MH' => 'MHL',
		'MR' => 'MRT',
		'MU' => 'MUS',
		'YT' => 'MYT',
		'MX' => 'MEX',
		'FM' => 'FSM',
		'MD' => 'MDA',
		'MC' => 'MCO',
		'MN' => 'MNG',
		'ME' => 'MNE',
		'MS' => 'MSR',
		'MA' => 'MAR',
		'MZ' => 'MOZ',
		'NA' => 'NAM',
		'NR' => 'NRU',
		'NP' => 'NPL',
		'NL' => 'NLD',
		'AN' => 'ANT',
		'NC' => 'NCL',
		'NZ' => 'NZL',
		'NI' => 'NIC',
		'NE' => 'NER',
		'NG' => 'NGA',
		'NU' => 'NIU',
		'KP' => 'PRK',
		'MP' => 'MNP',
		'NO' => 'NOR',
		'OM' => 'OMN',
		'PK' => 'PAK',
		'PW' => 'PLW',
		'PA' => 'PAN',
		'PG' => 'PNG',
		'PY' => 'PRY',
		'PE' => 'PER',
		'PH' => 'PHL',
		'PN' => 'PCN',
		'PL' => 'POL',
		'PT' => 'PRT',
		'PR' => 'PRI',
		'QA' => 'QAT',
		'CG' => 'COG',
		'RO' => 'ROU',
		'RU' => 'RUS',
		'RW' => 'RWA',
		'BL' => 'BLM',
		'SH' => 'SHN',
		'KN' => 'KNA',
		'LC' => 'LCA',
		'MF' => 'MAF',
		'PM' => 'SPM',
		'VC' => 'VCT',
		'WS' => 'WSM',
		'SM' => 'SMR',
		'ST' => 'STP',
		'SA' => 'SAU',
		'SN' => 'SEN',
		'RS' => 'SRB',
		'SC' => 'SYC',
		'SL' => 'SLE',
		'SG' => 'SGP',
		'SK' => 'SVK',
		'SI' => 'SVN',
		'SB' => 'SLB',
		'SO' => 'SOM',
		'ZA' => 'ZAF',
		'KR' => 'KOR',
		'ES' => 'ESP',
		'LK' => 'LKA',
		'SD' => 'SDN',
		'SR' => 'SUR',
		'SJ' => 'SJM',
		'SZ' => 'SWZ',
		'SE' => 'SWE',
		'CH' => 'CHE',
		'SY' => 'SYR',
		'TW' => 'TWN',
		'TJ' => 'TJK',
		'TZ' => 'TZA',
		'TH' => 'THA',
		'TL' => 'TLS',
		'TG' => 'TGO',
		'TK' => 'TKL',
		'TO' => 'TON',
		'TT' => 'TTO',
		'TN' => 'TUN',
		'TR' => 'TUR',
		'TM' => 'TKM',
		'TC' => 'TCA',
		'TV' => 'TUV',
		'UG' => 'UGA',
		'UA' => 'UKR',
		'AE' => 'ARE',
		'GB' => 'GBR',
		'US' => 'USA',
		'UY' => 'URY',
		'VI' => 'VIR',
		'UZ' => 'UZB',
		'VU' => 'VUT',
		'VE' => 'VEN',
		'VN' => 'VNM',
		'WF' => 'WLF',
		'EH' => 'ESH',
		'YE' => 'YEM',
		'ZM' => 'ZMB',
		'ZW' => 'ZWE',
	)
	
	//$_this =& Geography::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new Geography();
		}
		return $instance[0];
	}
	
	function getCountries($translate = true) {
		$_this =& Geography::getInstance();
		$countries = $_this->countries;
		
		if($translate){
			foreach ($countries as &$val) {
				$val = __d('geography',$val);
			}
		}
		
		return $countries;
	}
	
	function getCountry($code, $translate = true) {
		$_this =& Geography::getInstance();
		$code = strtoupper($code);
		if(strlen($name) == 3){
			$code = $_this->code3To2($name,true);
		}
		if(!isset($_this->countries[$code])){
			return false;
		}
		$val = $_this->countries[$code];
		
		if($translate){
			$val = __d('geography',$val);
		}
		
		return $val;
	}
	
	function code3To2($code,$returnDef = false){
		$_this =& Geography::getInstance();
		$ncode = strtoupper($code);
		$codes = array_flip($_this->threeLetterCodes);
		
		if(isset($codes[$ncode])){
			return $codes[$ncode];
		}elseif($returnDef ){
			return $code;
		}
		
		return null;
	}
	
	function code2To3($code,$returnDef = false){
		$_this =& Geography::getInstance();
		$ncode = strtoupper($code);
		$codes = $_this->threeLetterCodes;
		
		if(isset($codes[$ncode])){
			return $codes[$ncode];
		}elseif($returnDef ){
			return $code;
		}
		
		return null;
	}
	
	function getCountryCode($name,$returnDef = false) {
		$_this =& Geography::getInstance();
		$countries = $_this->countries;
		
		if(strlen($name) == 2){
			$code = strtoupper($name);
			if(isset($countries[$code])){
				return $code;
			}
		}
		if(strlen($name) == 3){
			$code = $_this->code3To2($name);
			if(!empty($code)){
				return $code;
			}
		}
		
		$normalRight = strtolower(Inflector::slug($name));
		foreach ($countries as $code => $val) {
			$normalLeft = strtolower(Inflector::slug(__d('geography',$val)));
			$normalLeft2 = strtolower(Inflector::slug($val));
			if($normalLeft == $normalRight || $normalLeft2 == $normalRight){
				return $code;
			}
		}
		if($returnDef){
			return $name;
		}
		return null;
	}
	
	function getRegions($country = null,$translate = true) {
		$_this =& Geography::getInstance();
		$countries = $_this->countries;
		if(is_null($country) || $country === true){
			$regions = array();
			foreach ($countries as $country => $r) {
				foreach ($r as $code => $region) {
					$regions[$country][$code] = __d('geography',$region);
				}
			}
			return $regions;
		}elseif(!empty($country)){
			$countryCode = $_this->getCountryCode($country);
			if(empty($_this->countries[$countryCode])){
				return false;
			}
			$regions = $_this->countries[$countryCode];
			if($translate){
				foreach ($regions as &$val) {
					$val = __d('geography',$val);
				}
			}
			return $regions;
		}
		return null;
	}
	
	function getLocalTerm($term, $country = null, $region = null, $translate = true){
		$_this =& Geography::getInstance();
		$res = $_this->localeTerms;
		if(!empty($country) && !empty($_this->localeTerms[$country])){
			$res = $_this->localeTerms[$country];
		}
		if(!empty($region) && is_array())
	}
	
	
}
?>
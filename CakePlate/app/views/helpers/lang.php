<?php 
/**
 * Outputs a country select list and/or a language select list. Automatically
 * detects language and country codes from browser headers.
 *
 * Usage...
 *
 *   echo $lang->countrySelect('Foo.country');
 *   echo $lang->languageSelect('Foo.language');
 *
 * You can override defaults such as:
 *
 *   echo $lang->countrySelect('Foo.country', array(
 *     'label' => __('Choose a Country', true),
 *     'default' => 'ru',
 *      'class' => 'some-class'
 *   ));
 *
 *   echo $lang->languageSelect('Foo.language', array(
 *     'label' => __('Choose a Language', true),
 *     'default' => 'sp',
 *     'class' => 'some-class'
 *   ));
 *
 * Note that the 'default' option is only used if the form was not previously
 * submitted, and country/language information could not be extracted from
 * the HTTP request.
 *
 * @note Some snippets taken from Tane Piper <digitalspaghetti@gmail.com>
 * @see http://digitalspaghetti.me.uk
 *
 * @author Brendon Crawford
 * @see http://aphexcreations.net
 *
 * @license
 *   Licensed under The MIT License
 *   Redistributions of files must retain the above copyright notice.
 *
 */


class LangHelper extends FormHelper {

	public $helpers = array('Form');
	private $mapper = array();

	private $countries = array(
		'af' =>	'Afganistan',
		'al' =>	'Albania',
		'dz' =>	'Algeria',
		'as' => 'American Samoa',
		'ad' => 'Andorra',
		'ao' => 'Angola',
		'ai' => 'Anguilla',
		'aq' => 'Antarctica',
		'ag' => 'Antigua and Barbuda',
		'ar' => 'Argentina',
		'am' => 'Armenia',
		'aw' => 'Aruba',
		'au' => 'Australia',
		'at' => 'Austria',
		'az' => 'Azerbaijan',
		'bs' => 'Bahamas',
		'bh' => 'Bahrain',
		'bd' => 'Bangladesh',
		'bb' => 'Barbados',
		'by' => 'Belarus',
		'be' => 'Belgium',
		'bz' => 'Belize',
		'bj' => 'Benin',
		'bm' => 'Bermuda',
		'bt' => 'Bhutan',
		'bo' => 'Bolivia',
		'ba' => 'Bosnia and Herzegowina',
		'bw' => 'Botswana',
		'bv' => 'Bouvet Island',
		'br' => 'Brazil',
		'io' => 'British Indian Ocean Territory',
		'bn' => 'Brunei Darussalam',
		'bg' => 'Bulgaria',
		'bf' => 'Burkina Faso',
		'bi' => 'Burundi',
		'kh' => 'Cambodia',
		'cm' => 'Cameroon',
		'ca' => 'Canada',
		'cv' => 'Cape Verde',
		'ky' => 'Cayman Islands',
		'cf' => 'Central African Republic',
		'td' => 'Chad',
		'cl' => 'Chile',
		'cn' => 'China',
		'cx' => 'Christmas Island',
		'cc' => 'Cocos Keeling Islands',
		'co' => 'Colombia',
		'km' => 'Comoros',
		'cg' => 'Congo',
		'cd' => 'Congo, Democratic Republic of the',
		'ck' => 'Cook Islands',
		'cr' => 'Costa Rica',
		'ci' => 'Cote d\'Ivoire',
		'hr' => 'Croatia Hrvatska',
		'cu' => 'Cuba',
		'cy' => 'Cyprus',
		'cz' => 'Czech Republic',
		'dk' => 'Denmark',
		'dj' => 'Djibouti',
		'dm' => 'Dominica',
		'do' => 'Dominican Republic',
		'tp' => 'East Timor',
		'ec' => 'Ecuador',
		'eg' => 'Egypt',
		'sv' => 'El Salvador',
		'gq' => 'Equatorial Guinea',
		'er' => 'Eritrea',
		'ee' => 'Estonia',
		'et' => 'Ethiopia',
		'fk' => 'Falkland Islands Malvinas',
		'fo' => 'Faroe Islands',
		'fj' => 'Fiji',
		'fi' => 'Finland',
		'fr' => 'France',
		'fx' => 'France, Metropolitan',
		'gf' => 'French Guiana',
		'pf' => 'French Polynesia',
		'tf' => 'French Southern Territories',
		'ga' => 'Gabon',
		'gm' => 'Gambia',
		'ge' => 'Georgia',
		'de' => 'Germany',
		'gh' => 'Ghana',
		'gi' => 'Gibraltar',
		'gr' => 'Greece',
		'gl' => 'Greenland',
		'gd' => 'Grenada',
		'gp' => 'Guadeloupe',
		'gu' => 'Guam',
		'gt' => 'Guatemala',
		'gn' => 'Guinea',
		'gw' => 'Guinea-Bissau',
		'gy' => 'Guyana',
		'ht' => 'Haiti',
		'hm' => 'Heard and Mc Donald Islands',
		'va' => 'Holy See (Vatican City State)',
		'hn' => 'Honduras',
		'hk' => 'Hong Kong',
		'hu' => 'Hungary',
		'is' => 'Iceland',
		'in' => 'India',
		'id' => 'Indonesia',
		'ir' => 'Iran, Islamic Republic of',
		'iq' => 'Iraq',
		'ie' => 'Ireland',
		'il' => 'Israel',
		'it' => 'Italy',
		'hm' => 'Jamaica',
		'jp' => 'Japan',
		'jo' => 'Jordan',
		'kz' => 'Kazakhstan',
		'ke' => 'Kenya',
		'ki' => 'Kiribati',
		'kp' => 'Korea, Democratic People\'s Republic of',
		'kr' => 'Korea, Republic of',
		'kw' => 'Kuwait',
		'kg' => 'Kyrgyzstan',
		'la' => 'Lao People\'s Democratic Republic',
		'lv' => 'Latvia',
		'lb' => 'Lebanon',
		'ls' => 'Lesotho',
		'lr' => 'Liberia',
		'ly' => 'Libyan Arab Jamahiriya',
		'li' => 'Liechtenstein',
		'lt' => 'Lithuania',
		'lu' => 'Luxembourg',
		'mo' => 'Macau',
		'mk' => 'Macedonia, The Former Yugoslav Republic of',
		'mg' => 'Madagascar',
		'mw' => 'Malawi',
		'my' => 'Malaysia',
		'mv' => 'Maldives',
		'ml' => 'Mali',
		'mt' => 'Malta',
		'mh' => 'Marshall Islands',
		'mq' => 'Martinique',
		'mr' => 'Mauritania',
		'mu' => 'Mauritius',
		'yt' => 'Mayotte',
		'mx' => 'Mexico',
		'fm' => 'Micronesia, Federated States of',
		'md' => 'Moldova, Republic of',
		'mc' => 'Monaco',
		'mn' => 'Mongolia',
		'ms' => 'Montserrat',
		'ma' => 'Morocco',
		'mz' => 'Mozambique',
		'mm' => 'Myanmar',
		'na' => 'Namibia',
		'nr' => 'Nauru',
		'np' => 'Nepal',
		'nl' => 'Netherlands',
		'an' => 'Netherlands Antilles',
		'nc' => 'New Caledonia',
		'nz' => 'New Zealand',
		'ni' => 'Nicaragua',
		'ne' => 'Niger',
		'ng' => 'Nigeria',
		'nu' => 'Niue',
		'nf' => 'Norfolk Island',
		'mp' => 'Northern Mariana Islands',
		'no' => 'Norway',
		'om' => 'Oman',
		'pk' => 'Pakistan',
		'pw' => 'Palau',
		'pa' => 'Panama',
		'pg' => 'Papua New Guinea',
		'py' => 'Paraguay',
		'pe' => 'Peru',
		'ph' => 'Philippines',
		'pn' => 'Pitcairn',
		'pl' => 'Poland',
		'pt' => 'Portugal',
		'pr' => 'Puerto Rico',
		'qa' => 'Qatar',
		're' => 'Reunion',
		'ro' => 'Romania',
		'ru' => 'Russian Federation',
		'rw' => 'Rwanda',
		'kn' => 'Saint Kitts and Nevis',
		'lc' => 'Saint LUCIA',
		'vc' => 'Saint Vincent and the Grenadines',
		'ws' => 'Samoa',
		'sm' => 'San Marino',
		'st' => 'Sao Tome and Principe',
		'sa' => 'Saudi Arabia',
		'sn' => 'Senegal',
		'sc' => 'Seychelles',
		'sl' => 'Sierra Leone',
		'sg' => 'Singapore',
		'sk' => 'Slovakia (Slovak Republic)',
		'si' => 'Slovenia',
		'sb' => 'Solomon Islands',
		'so' => 'Somalia',
		'za' => 'South Africa',
		'gs' => 'South Georgia and the South Sandwich Islands',
		'es' => 'Spain',
		'lk' => 'Sri Lanka',
		'sh' => 'St. Helena',
		'pm' => 'St. Pierre and Miquelon',
		'sd' => 'Sudan',
		'sr' => 'Suriname',
		'sj' => 'Svalbard and Jan Mayen Islands',
		'sz' => 'Swaziland',
		'se' => 'Sweden',
		'ch' => 'Switzerland',
		'sy' => 'Syrian Arab Republic',
		'tw' => 'Taiwan, Province of China',
		'tj' => 'Tajikistan',
		'tz' => 'Tanzania, United Republic of',
		'th' => 'Thailand',
		'tg' => 'Togo',
		'tk' => 'Tokelau',
		'to' => 'Tonga',
		'tt' => 'Trinidad and Tobago',
		'tn' => 'Tunisia',
		'tr' => 'Turkey',
		'tm' => 'Turkmenistan',
		'tc' => 'Turks and Caicos Islands',
		'tv' => 'Tuvalu',
		'ug' => 'Uganda',
		'ua' => 'Ukraine',
		'ae' => 'United Arab Emirates',
		'gb' => 'United Kingdom',
		'us' => 'United States',
		'um' => 'United States Minor Outlying Islands',
		'uy' => 'Uruguay',
		'uz' => 'Uzbekistan',
		'vu' => 'Vanuatu',
		've' => 'Venezuela',
		'vn' => 'Viet Nam',
		'vg' => 'Virgin Islands (British)',
		'vi' => 'Virgin Islands (U.S.)',
		'wf' => 'Wallis and Futuna Islands',
		'eh' => 'Western Sahara',
		'ye' => 'Yemen',
		'yu' => 'Yugoslavia',
		'zm' => 'Zambia',
		'zw' => 'Zimbabwe'
	);

	private $languages = array(
		'ab' => 'Abkhazian',
		'aa' => 'Afar',
		'af' => 'Afrikaans',
		'ak' => 'Akan',
		'sq' => 'Albanian',
		'am' => 'Amharic',
		'ar' => 'Arabic',
		'an' => 'Aragonese',
		'hy' => 'Armenian',
		'as' => 'Assamese',
		'av' => 'Avaric',
		'ae' => 'Avestan',
		'ay' => 'Aymara',
		'az' => 'Azerbaijani',
		'bm' => 'Bambara',
		'ba' => 'Bashkir',
		'eu' => 'Basque',
		'be' => 'Belarusian',
		'bn' => 'Bengali',
		'bh' => 'Bihari',
		'bi' => 'Bislama',
		'nb' => 'Bokmal',
		'bs' => 'Bosnian',
		'br' => 'Breton',
		'bg' => 'Bulgarian',
		'my' => 'Burmese',
		'ca' => 'Catalan',
		'km' => 'Central Khmer',
		'ch' => 'Chamorro',
		'ce' => 'Chechen',
		'ny' => 'Chewa',
		'zh' => 'Chinese',
		'cu' => 'Church Slavic',
		'cv' => 'Chuvash',
		'kw' => 'Cornish',
		'co' => 'Corsican',
		'cr' => 'Cree',
		'hr' => 'Croatian',
		'cs' => 'Czech',
		'da' => 'Danish',
		'dv' => 'Dhivehi',
		'nl' => 'Dutch',
		'dz' => 'Dzongkha',
		'en' => 'English',
		'eo' => 'Esperanto',
		'et' => 'Estonian',
		'ee' => 'Ewe',
		'fo' => 'Faroese',
		'fj' => 'Fijian',
		'fi' => 'Finnish',
		'fr' => 'French',
		'ff' => 'Fulah',
		'gd' => 'Gaelic',
		'gl' => 'Galician',
		'lg' => 'Ganda',
		'ka' => 'Georgian',
		'de' => 'German',
		'ki' => 'Gikuyu',
		'el' => 'Greek',
		'kl' => 'Greenlandic',
		'gn' => 'Guarani',
		'gu' => 'Gujarati',
		'ht' => 'Haitian',
		'ha' => 'Hausa',
		'he' => 'Hebrew',
		'hz' => 'Herero',
		'hi' => 'Hindi',
		'ho' => 'Hiri Motu',
		'hu' => 'Hungarian',
		'is' => 'Icelandic',
		'io' => 'Ido',
		'ig' => 'Igbo',
		'id' => 'Indonesian',
		'ia' => 'Interlingua',
		'iu' => 'Inuktitut',
		'ik' => 'Inupiaq',
		'ga' => 'Irish',
		'it' => 'Italian',
		'ja' => 'Japanese',
		'jv' => 'Javanese',
		'kn' => 'Kannada',
		'kr' => 'Kanuri',
		'ks' => 'Kashmiri',
		'kk' => 'Kazakh',
		'rw' => 'Kinyarwanda',
		'kv' => 'Komi',
		'kg' => 'Kongo',
		'ko' => 'Korean',
		'ku' => 'Kurdish',
		'kj' => 'Kwanyama',
		'ky' => 'Kyrgyz',
		'lo' => 'Lao',
		'la' => 'Latin',
		'lv' => 'Latvian',
		'lb' => 'Letzeburgesch',
		'li' => 'Limburgan',
		'ln' => 'Lingala',
		'lt' => 'Lithuanian',
		'lu' => 'Luba-Katanga',
		'mk' => 'Macedonian',
		'mg' => 'Malagasy',
		'ms' => 'Malay',
		'ml' => 'Malayalam',
		'mt' => 'Maltese',
		'gv' => 'Manx',
		'mi' => 'Maori',
		'mr' => 'Marathi',
		'mh' => 'Marshallese',
		'ro' => 'Moldavian',
		'mn' => 'Mongolian',
		'na' => 'Nauru',
		'nv' => 'Navajo',
		'ng' => 'Ndonga',
		'ne' => 'Nepali',
		'nd' => 'North Ndebele',
		'se' => 'Northern Sami',
		'no' => 'Norwegian',
		'nn' => 'Norwegian Nynorsk',
		'ie' => 'Occidental',
		'oc' => 'Occitan',
		'oj' => 'Ojibwa',
		'or' => 'Oriya',
		'om' => 'Oromo',
		'os' => 'Ossetian',
		'pi' => 'Pali',
		'fa' => 'Persian',
		'pl' => 'Polish',
		'pt' => 'Portuguese',
		'pa' => 'Punjabi',
		'ps' => 'Pushto',
		'qu' => 'Quechua',
		'ro' => 'Romanian',
		'rm' => 'Romansh',
		'rn' => 'Rundi',
		'ru' => 'Russian',
		'sm' => 'Samoan',
		'sg' => 'Sango',
		'sa' => 'Sanskrit',
		'sc' => 'Sardinian',
		'sr' => 'Serbian',
		'sn' => 'Shona',
		'ii' => 'Sichuan Yi',
		'sd' => 'Sindhi',
		'si' => 'Sinhalese',
		'sk' => 'Slovak',
		'sl' => 'Slovenian',
		'so' => 'Somali',
		'st' => 'Southern Sotho',
		'nr' => 'South Ndebele',
		'es' => 'Spanish',
		'su' => 'Sundanese',
		'sw' => 'Swahili',
		'ss' => 'Swati',
		'sv' => 'Swedish',
		'tl' => 'Tagalog',
		'ty' => 'Tahitian',
		'tg' => 'Tajik',
		'ta' => 'Tamil',
		'tt' => 'Tatar',
		'te' => 'Telugu',
		'th' => 'Thai',
		'bo' => 'Tibetan',
		'ti' => 'Tigrinya',
		'to' => 'Tonga',
		'ts' => 'Tsonga',
		'tn' => 'Tswana',
		'tr' => 'Turkish',
		'tk' => 'Turkmen',
		'tw' => 'Twi',
		'uk' => 'Ukrainian',
		'ur' => 'Urdu',
		'ug' => 'Uyghur',
		'uz' => 'Uzbek',
		've' => 'Venda',
		'vi' => 'Vietnamese',
		'vo' => 'VolapŸk',
		'wa' => 'Walloon',
		'cy' => 'Welsh',
		'fy' => 'Western Frisian',
		'wo' => 'Wolof',
		'xh' => 'Xhosa',
		'yi' => 'Yiddish',
		'yo' => 'Yoruba',
		'za' => 'Zhuang',
		'zu' => 'Zulu'
	);

	private $defaultLang = 'en';
	private $defaultCountry = 'us';
	private $langCode = null;
	private $countryCode = null;

	/**
	 * @constructor
	 */
	public function __construct() {
		$this->mapper = $this->parseLangHeaders();
		$this->langCode = $this->findLangCode();
		$this->countryCode = $this->findCountryCode();
	}

	/**
	 * Sets Defaults
	 *
	 * @param string $lang
	 * @param string|null $country optional
	 * @return bool
	 */
	public function setDefaults($lang, $country=null) {
		$this->defaultLang = $lang;
		if ($country !== null) {
			$this->defaultCountry = $country;
		}
		return true;
	}

	/**
	 * Finds Lang Code
	 *
	 * @return string|null
	 */
	private function findLangCode() {
		reset($this->mapper);
		$f = current($this->mapper);
		if ($f === false) {
			return null;
		}
		else {
			return $f->language;
		}
	}

	/**
	 * Finds Country Code
	 *
	 * @return string|null
	 */
	private function findCountryCode() {
		reset($this->mapper);
		foreach ($this->mapper as $map) {
			if ($map->country !== null) {
				return $map->country;
			}
		}
		return null;
	}

	/**
	 * Parses HTTP Request Language Headers
	 *
	 * @param string $accept
	 * @return array
	 */
	private function parseLangHeaders($accept=null) {
		if ($accept === null) {
			$langHead = env('HTTP_ACCEPT_LANGUAGE');
		}
		else {
			$langHead = (string)$accept;
		}
		$langs = preg_split('/\s*,\s*/i', $langHead, -1, PREG_SPLIT_NO_EMPTY);
		$out = array();
		$i = 0;
		$weightIndex = 1;
		foreach ($langs as $lang) {
			$opts = preg_split('/\s*;\s*/i', $lang, -1, PREG_SPLIT_NO_EMPTY);
			$code = $opts[0];
			$weight = null;
			$codeSegs = explode('-', $code);
			$langCode = strtolower($codeSegs[0]);
			$ctryCode = null;
			if (array_key_exists(1, $codeSegs)) {
				$ctryCode = strtolower($codeSegs[1]);
			}
			if (array_key_exists(1, $opts)) {
				$qParams = explode('=', $opts[1]);
				if ($qParams[0] === 'q') {
					if (array_key_exists(1, $qParams) && is_numeric($qParams[1])) {
						$weight = (float)$qParams[1];
					}
				}
			}
			if ($weight === null) {
				$weight = $weightIndex;
			}
			$out[] = (object)array(
				'language' => $langCode,
				'country' => $ctryCode,
				'weight' => $weight
			);
			$i++;
			if ($weightIndex > 0) {
				$weightIndex -= .1;
			}
		}
		uasort($out, array($this, 'weightSort'));
		return $out;
	}

	/**
	 * Sorts request lang code weights
	 *
	 * @param object $a
	 * @param object $b
	 * @return int
	 */
	public function weightSort($a, $b) {
		if ($a->weight === $b->weight) {
			return 0;
		}
		elseif ($a->weight > $b->weight) {
			return -1;
		}
		else {
			return 1;
		}
	}

	/**
	 * Finds selected element
	 *
	 * @param string $fieldName
	 * @return assoc
	 */
	private function getSelected($fieldName) {
		if (empty($this->data)) {
			return null;
		}
		$view =& ClassRegistry::getObject('view');
		$this->setEntity($fieldName);
		$ent = $view->entity();
		if (empty($ent)) {
			return null;
		}
		$obj = $this->data;
		$i = 0;
		while (true) {
			if (is_array($obj)) {
				if (array_key_exists($ent[$i], $obj)) {
					$obj = $obj[$ent[$i]];
					$i++;
				}
			}
			else {
				return $obj;
			}
		}
	}

	/**
	 * Outputs country list
	 *
	 * @param string $fieldName
	 * @param assoc $options
	 * @return string
	 */
	public function countrySelect($fieldName, $options=array()) {
		$options = array_merge(array(
			'label' => __('Country', true),
			'default' => $this->defaultCountry,
			'class' => null
		), $options);
		$selected = $this->getSelected($fieldName);
		if ($selected === null ||
				!array_key_exists($selected, $this->countries)) {
			if ($this->countryCode === null) {
				$selected = $options['default'];
			}
			else {
				$selected = $this->countryCode;
			}
		}
		$opts = array();
		$opts['options'] = $this->countries;
		$opts['selected'] = $selected;
		$opts['multiple'] = false;
		$opts['label'] = $options['label'];
		if ($options['class'] !== null) {
			$opts['class'] = $options['class'];
		}
		$out = $this->Form->input($fieldName, $opts);
		return $this->output($out);
	}

	/**
	 * Outputs language list
	 *
	 * @param string $fieldName
	 * @param assoc $options
	 * @return string
	 */
	public function languageSelect($fieldName, $options=array()) {
		$options = array_merge(array(
			'label' => __('Language', true),
			'default' => $this->defaultLang,
			'class' => null
		), $options);
		$selected = $this->getSelected($fieldName);
		if ($selected === null ||
				!array_key_exists($selected, $this->languages)) {
			if ($this->langCode === null) {
				$selected = $options['default'];
			}
			else {
				$selected = $this->langCode;
			}
		}
		$opts = array();
		$opts['options'] = $this->languages;
		$opts['selected'] = $selected;
		$opts['multiple'] = false;
		$opts['label'] = $options['label'];
		if ($options['class'] !== null) {
			$opts['class'] = $options['class'];
		}
		$out = $this->Form->input($fieldName, $opts);
		return $this->output($out);
	}

}
?>
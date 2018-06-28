<?php

/**
 * Translates characters with diacritical marks to simple equivalents
 *
 * @author Stephen Billard (sbillard)
 *
 * @package plugins/seo_zenphoto
 * @pluginCategory seo
 */
$plugin_is_filter = defaultExtension(5 | ADMIN_PLUGIN);
$plugin_description = gettext("SEO filter to translate extended characters into their basic alpha-numeric equivalents.");
$plugin_disable = (zp_has_filter('seoFriendly') && !extensionEnabled('seo_zenphoto')) ? sprintf(gettext('Only one SEO filter plugin may be enabled. <a href="#%1$s"><code>%1$s</code></a> is already enabled.'), stripSuffix(get_filterScript('seoFriendly'))) : '';

$option_interface = 'zenphoto_seo';

if ($plugin_disable) {
	enableExtension('zenphoto_seo', 0);
} else {
	zp_register_filter('seoFriendly', 'zenphoto_seo::filter');
	zp_register_filter('seoFriendly_js', 'zenphoto_seo::js');
}

/**
 * Option handler class
 *
 */
class zenphoto_seo {

	/**
	 * class instantiation function
	 *
	 * @return zenphoto_seo
	 */
	function __construct() {
		if (OFFSET_PATH == 2) {
			setOptionDefault('zenphoto_seo_lowercase', 1);
		}
	}

	/**
	 * Reports the supported options
	 *
	 * @return array
	 */
	function getOptionsSupported() {
		return array(gettext('Lowercase only') => array('key' => 'zenphoto_seo_lowercase', 'type' => OPTION_TYPE_CHECKBOX,
						'desc' => gettext('When set, all characters are converted to lower case.')));
	}

	function handleOption($option, $currentValue) {

	}

	private static $specialchars = array(
			"À" => "A",
			"Á" => "A",
			"Â" => "A",
			"Ã" => "A",
			"Å" => "A",
			"Ā" => "A",
			"Ă" => "A",
			"Ą" => "A",
			"Ǟ" => "A",
			"Ǡ" => "A",
			"Ǻ" => "A",
			"Ȁ" => "A",
			"Ȃ" => "A",
			"Ȧ" => "A",
			"Ḁ" => "A",
			"Ả" => "A",
			"Ấ" => "A",
			"Ầ" => "A",
			"Ẩ" => "A",
			"Ẫ" => "A",
			"Ậ" => "A",
			"Ắ" => "A",
			"Ằ" => "A",
			"Ẳ" => "A",
			"Ẵ" => "A",
			"Ặ" => "A",
			"Å" => "A",
			"Ä" => "AE",
			"Æ" => "AE",
			"Ǽ" => "AE",
			"Ǣ" => "AE",
			"Ḃ" => "B",
			"Ɓ" => "B",
			"Ḅ" => "B",
			"Ḇ" => "B",
			"Ƃ" => "B",
			"Ƅ" => "B",
			"Ć" => "C",
			"Ĉ" => "C",
			"Ċ" => "C",
			"Č" => "C",
			"Ƈ" => "C",
			"Ç" => "C",
			"Ḉ" => "C",
			"Ḋ" => "D",
			"Ɗ" => "D",
			"Ḍ" => "D",
			"Ḏ" => "D",
			"Ḑ" => "D",
			"Ḓ" => "D",
			"Ď" => "D",
			"Đ" => "D",
			"Ɖ" => "D",
			"È" => "E",
			"É" => "E",
			"Ê" => "E",
			"Ẽ" => "E",
			"Ē" => "E",
			"Ĕ" => "E",
			"Ė" => "E",
			"Ë" => "E",
			"Ẻ" => "E",
			"Ě" => "E",
			"Ȅ" => "E",
			"Ȇ" => "E",
			"Ẹ" => "E",
			"Ȩ" => "E",
			"Ę" => "E",
			"Ḙ" => "E",
			"Ḛ" => "E",
			"Ề" => "E",
			"Ế" => "E",
			"Ễ" => "E",
			"Ể" => "E",
			"Ḕ" => "E",
			"Ḗ" => "E",
			"Ệ" => "E",
			"Ḝ" => "E",
			"Ǝ" => "E",
			"Ɛ" => "E",
			"Ḟ" => "F",
			"Ƒ" => "F",
			"Ǵ" => "G",
			"Ĝ" => "G",
			"Ḡ" => "G",
			"Ğ" => "G",
			"Ġ" => "G",
			"Ǧ" => "G",
			"Ɠ" => "G",
			"Ģ" => "G",
			"Ǥ" => "G",
			"Ĥ" => "H",
			"Ḣ" => "H",
			"Ḧ" => "H",
			"Ȟ" => "H",
			"Ƕ" => "H",
			"Ḥ" => "H",
			"Ḩ" => "H",
			"Ḫ" => "H",
			"Ħ" => "H",
			"Ì" => "I",
			"Í" => "I",
			"Î" => "I",
			"Ĩ" => "I",
			"Ī" => "I",
			"Ĭ" => "I",
			"İ" => "I",
			"Ï" => "I",
			"Ỉ" => "I",
			"Ǐ" => "I",
			"Ị" => "I",
			"Į" => "I",
			"Ȋ" => "I",
			"Ḭ" => "I",
			"Ḭ" => "I",
			"Ɨ" => "I",
			"Ḯ" => "I",
			"Ĵ" => "J",
			"Ḱ" => "K",
			"Ǩ" => "K",
			"Ḵ" => "K",
			"Ƙ" => "K",
			"Ḳ" => "K",
			"Ķ" => "K",
			"Ḻ" => "L",
			"Ḷ" => "L",
			"Ḷ" => "L",
			"Ļ" => "L",
			"Ḽ" => "L",
			"Ľ" => "L",
			"Ŀ" => "L",
			"Ł" => "L",
			"Ḹ" => "L",
			"Ḿ" => "M",
			"Ṁ" => "M",
			"Ṃ" => "M",
			"Ɯ" => "M",
			"Ǹ" => "N",
			"Ń" => "N",
			"Ñ" => "N",
			"Ṅ" => "N",
			"Ň" => "N",
			"Ŋ" => "N",
			"Ɲ" => "N",
			"Ṇ" => "N",
			"Ņ" => "N",
			"Ṋ" => "N",
			"Ṉ" => "N",
			"Ƞ" => "N",
			"Ò" => "O",
			"Ó" => "O",
			"Ô" => "O",
			"Õ" => "O",
			"Ō" => "O",
			"Ŏ" => "O",
			"Ȍ" => "O",
			"Ȏ" => "OE",
			"Ơ" => "O",
			"Ǫ" => "O",
			"Ọ" => "O",
			"Ɵ" => "O",
			"Ồ" => "O",
			"Ố" => "O",
			"Ỗ" => "O",
			"Ổ" => "O",
			"Ȱ" => "O",
			"Ȫ" => "O",
			"Ȭ" => "O",
			"Ṍ" => "O",
			"Ṏ" => "O",
			"Ṑ" => "O",
			"Ṓ" => "O",
			"Ờ" => "O",
			"Ớ" => "O",
			"Ỡ" => "O",
			"Ở" => "O",
			"Ǭ" => "O",
			"Ộ" => "O",
			"Ɔ" => "O",
			"Ø" => "OE",
			"Ǿ" => "OE",
			"Œ" => "OE",
			"Ṕ" => "P",
			"Ṗ" => "P",
			"Ƥ" => "P",
			"Ŕ" => "R",
			"Ṙ" => "R",
			"Ř" => "R",
			"Ȑ" => "R",
			"Ȓ" => "R",
			"Ṛ" => "R",
			"Ŗ" => "R",
			"Ṟ" => "R",
			"Ṝ" => "R",
			"Ʀ" => "R",
			"Ś" => "S",
			"Ŝ" => "S",
			"Ṡ" => "S",
			"Š" => "S",
			"Ṣ" => "S",
			"Ș" => "S",
			"Ş" => "S",
			"Ṥ" => "S",
			"Ṧ" => "S",
			"Ṩ" => "S",
			"Ṫ" => "T",
			"Ť" => "T",
			"Ƭ" => "T",
			"Ʈ" => "T",
			"Ṭ" => "T",
			"Ț" => "T",
			"Ţ" => "T",
			"Ṱ" => "T",
			"Ṯ" => "T",
			"Ŧ" => "T",
			"Ù" => "U",
			"Ú" => "U",
			"Û" => "U",
			"Ũ" => "U",
			"Ū" => "U",
			"Ŭ" => "U",
			"Ủ" => "U",
			"Ů" => "U",
			"Ű" => "U",
			"Ǔ" => "U",
			"Ȕ" => "U",
			"Ȗ" => "U",
			"Ư" => "U",
			"Ụ" => "U",
			"Ṳ" => "U",
			"Ų" => "U",
			"Ṷ" => "U",
			"Ṵ" => "U",
			"Ṹ" => "U",
			"Ṻ" => "U",
			"Ǜ" => "U",
			"Ǘ" => "U",
			"Ǖ" => "U",
			"Ǚ" => "U",
			"Ừ" => "U",
			"Ứ" => "U",
			"Ữ" => "U",
			"Ử" => "U",
			"Ự" => "U",
			"Ü" => "UE",
			"Ṽ" => "V",
			"Ṿ" => "V",
			"Ʋ" => "V",
			"Ẁ" => "W",
			"Ẃ" => "W",
			"Ŵ" => "W",
			"Ẇ" => "W",
			"Ẅ" => "W",
			"Ẉ" => "W",
			"Ẋ" => "X",
			"Ẍ" => "X",
			"Ỳ" => "Y",
			"Ý" => "Y",
			"Ŷ" => "Y",
			"Ỹ" => "Y",
			"Ȳ" => "Y",
			"Ẏ" => "Y",
			"Ÿ" => "Y",
			"Ỷ" => "Y",
			"Ƴ" => "Y",
			"Ỵ" => "Y",
			"Ź" => "Z",
			"Ẑ" => "Z",
			"Ż" => "Z",
			"Ž" => "Z",
			"Ȥ" => "Z",
			"Ẓ" => "Z",
			"Ẕ" => "Z",
			"Ƶ" => "Z",
			"à" => "a",
			"á" => "a",
			"â" => "a",
			"ã" => "a",
			"ā" => "a",
			"ă" => "a",
			"ȧ" => "a",
			"ả" => "a",
			"å" => "a",
			"ǎ" => "a",
			"ȁ" => "a",
			"ȃ" => "a",
			"ạ" => "a",
			"ḁ" => "a",
			"ẚ" => "a",
			"ầ" => "a",
			"ấ" => "a",
			"ẫ" => "a",
			"ẩ" => "a",
			"ằ" => "a",
			"ắ" => "a",
			"ẵ" => "a",
			"ẳ" => "a",
			"ǡ" => "a",
			"ǟ" => "a",
			"ǻ" => "a",
			"ậ" => "a",
			"ặ" => "a",
			"ǽ" => "ae",
			"ä" => "ae",
			"ḃ" => "b",
			"ɓ" => "b",
			"ḅ" => "b",
			"ḇ" => "b",
			"ƀ" => "b",
			"ƃ" => "b",
			"ƅ" => "b",
			"c" => "c",
			"ć" => "c",
			"ĉ" => "c",
			"ċ" => "c",
			"č" => "c",
			"ƈ" => "c",
			"ç" => "c",
			"ḉ" => "c",
			"ḍ" => "d",
			"ḏ" => "d",
			"ḑ" => "d",
			"ḓ" => "d",
			"ď" => "d",
			"đ" => "d",
			"ƌ" => "d",
			"ȡ" => "d",
			"è" => "e",
			"é" => "e",
			"ê" => "e",
			"ẽ" => "e",
			"ē" => "e",
			"ĕ" => "e",
			"ė" => "e",
			"ë" => "e",
			"ě" => "e",
			"ȅ" => "e",
			"ȇ" => "e",
			"ẹ" => "e",
			"ȩ" => "e",
			"ę" => "e",
			"ḙ" => "e",
			"ề" => "e",
			"ế" => "e",
			"ễ" => "e",
			"ể" => "e",
			"ḕ" => "e",
			"ḗ" => "e",
			"ệ" => "e",
			"ḝ" => "e",
			"ǝ" => "e",
			"ɛ" => "e",
			"ḟ" => "f",
			"ƒ" => "f",
			"ǵ" => "g",
			"ĝ" => "g",
			"ḡ" => "g",
			"ğ" => "g",
			"ġ" => "g",
			"ǧ" => "g",
			"ɠ" => "g",
			"ģ" => "g",
			"ǥ" => "g",
			"ĥ" => "h",
			"ḣ" => "h",
			"ḧ" => "h",
			"ȟ" => "h",
			"ƕ" => "h",
			"ḥ" => "h",
			"ḩ" => "h",
			"ḫ" => "h",
			"ẖ" => "h",
			"ħ" => "h",
			"ì" => "i",
			"í" => "i",
			"î" => "i",
			"ĩ" => "i",
			"ī" => "i",
			"ĭ" => "i",
			"ı" => "i",
			"ï" => "i",
			"ỉ" => "i",
			"ǐ" => "i",
			"ị" => "i",
			"į" => "i",
			"ȉ" => "i",
			"ȋ" => "i",
			"ḭ" => "i",
			"ɨ" => "i",
			"ḯ" => "i",
			"ĵ" => "j",
			"ǰ" => "j",
			"ḱ" => "k",
			"ǩ" => "k",
			"ḵ" => "k",
			"ƙ" => "k",
			"ḳ" => "k",
			"ķ" => "k",
			"ĺ" => "l",
			"ḻ" => "l",
			"ḷ" => "l",
			"ļ" => "l",
			"ḽ" => "l",
			"ľ" => "l",
			"ŀ" => "l",
			"ł" => "l",
			"ƚ" => "l",
			"ḹ" => "l",
			"ȴ" => "l",
			"ḿ" => "m",
			"ṁ" => "m",
			"ṃ" => "m",
			"ɯ" => "m",
			"ǹ" => "n",
			"ń" => "n",
			"ñ" => "n",
			"ṅ" => "n",
			"ň" => "n",
			"ŋ" => "n",
			"ɲ" => "n",
			"ṇ" => "n",
			"ņ" => "n",
			"ṋ" => "n",
			"ṉ" => "n",
			"ŉ" => "n",
			"ƞ" => "n",
			"ȵ" => "n",
			"ò" => "o",
			"ó" => "o",
			"ô" => "o",
			"õ" => "o",
			"ō" => "o",
			"ŏ" => "o",
			"ȯ" => "o",
			"ỏ" => "o",
			"ő" => "o",
			"ǒ" => "o",
			"ȍ" => "o",
			"ȏ" => "o",
			"ơ" => "o",
			"ǫ" => "o",
			"ọ" => "o",
			"ɵ" => "o",
			"ồ" => "o",
			"ố" => "o",
			"ỗ" => "o",
			"ổ" => "o",
			"ȱ" => "o",
			"ȫ" => "o",
			"ȭ" => "o",
			"ṍ" => "o",
			"ṏ" => "o",
			"ṑ" => "o",
			"ṓ" => "o",
			"ờ" => "o",
			"ớ" => "o",
			"ỡ" => "o",
			"ở" => "o",
			"ợ" => "o",
			"ǭ" => "o",
			"ộ" => "o",
			"ǿ" => "o",
			"ɔ" => "o",
			"ø" => "oe",
			"œ" => "oe",
			"ṕ" => "p",
			"ṗ" => "p",
			"ƥ" => "p",
			"ŕ" => "p",
			"ṙ" => "p",
			"ř" => "p",
			"ȑ" => "p",
			"ȓ" => "p",
			"ṛ" => "p",
			"ŗ" => "p",
			"ṟ" => "p",
			"ṝ" => "p",
			"ś" => "s",
			"ŝ" => "s",
			"ṡ" => "s",
			"š" => "s",
			"ṣ" => "s",
			"ș" => "s",
			"ş" => "s",
			"ṥ" => "s",
			"ṧ" => "s",
			"ṩ" => "s",
			"ß" => "ss",
			"ẛ" => "t",
			"ṫ" => "t",
			"ẗ" => "t",
			"ť" => "t",
			"ƭ" => "t",
			"ʈ" => "t",
			"ƫ" => "t",
			"ṭ" => "t",
			"ț" => "t",
			"ţ" => "t",
			"ṱ" => "t",
			"ṯ" => "t",
			"ŧ" => "t",
			"ȶ" => "t",
			"ù" => "u",
			"ú" => "u",
			"û" => "u",
			"ũ" => "u",
			"ū" => "u",
			"ŭ" => "u",
			"ủ" => "u",
			"ů" => "u",
			"ű" => "u",
			"ǔ" => "u",
			"ȕ" => "u",
			"ȗ" => "u",
			"ư" => "u",
			"ụ" => "u",
			"ṳ" => "u",
			"ų" => "u",
			"ṷ" => "u",
			"ṵ" => "u",
			"ṹ" => "u",
			"ṻ" => "u",
			"ǖ" => "u",
			"ǜ" => "u",
			"ǘ" => "u",
			"ǖ" => "u",
			"ǚ" => "u",
			"ừ" => "u",
			"ứ" => "u",
			"ữ" => "u",
			"ử" => "u",
			"ự" => "u",
			"ṿ" => "u",
			"ü" => "ue",
			"ṽ" => "v",
			"ẁ" => "w",
			"ẃ" => "w",
			"ŵ" => "w",
			"ẇ" => "w",
			"ẅ" => "w",
			"ẘ" => "w",
			"ẉ" => "w",
			"ẋ" => "x",
			"ẍ" => "x",
			"ỳ" => "y",
			"ý" => "y",
			"ŷ" => "y",
			"ỹ" => "y",
			"ȳ" => "y",
			"ẏ" => "y",
			"ÿ" => "y",
			"ỷ" => "y",
			"ẙ" => "y",
			"ƴ" => "y",
			"ỵ" => "y",
			"ź" => "z",
			"ẑ" => "z",
			"ż" => "z",
			"ž" => "z",
			"ȥ" => "z",
			"ẓ" => "z",
			"ẕ" => "z",
			"ƶ" => "z",
			"¨" => "",
			"'" => "-",
			"’" => "-",
			"΅" => "",
			"΄" => "",
			"ͺ" => "",
			"–" => "-",
			"᾿" => "",
			"῾" => "",
			"῍" => "",
			"῝" => "",
			"῎" => "",
			"῞" => "",
			"῏" => "",
			"῟" => "",
			"῀" => "",
			"῁" => "",
			"΅" => "",
			"`" => "",
			"῭" => "",
			"᾽" => "",
			"ἀ" => "a",
			"ἁ" => "a",
			"ἂ" => "a",
			"ἃ" => "a",
			"ἄ" => "a",
			"ἅ" => "a",
			"ἆ" => "a",
			"ἇ" => "a",
			"ᾀ" => "a",
			"ᾁ" => "a",
			"ᾂ" => "a",
			"ᾃ" => "a",
			"ᾄ" => "a",
			"ᾅ" => "a",
			"ᾆ" => "a",
			"ᾇ" => "a",
			"ὰ" => "a",
			"ά" => "a",
			"ᾰ" => "a",
			"ᾱ" => "a",
			"ᾲ" => "a",
			"ᾳ" => "a",
			"ᾴ" => "a",
			"ᾶ" => "a",
			"ᾷ" => "a",
			"ა" => "a",
			"Ἀ" => "A",
			"Ἁ" => "A",
			"Ἂ" => "A",
			"Ἃ" => "A",
			"Ἄ" => "A",
			"Ἅ" => "A",
			"Ἆ" => "A",
			"Ἇ" => "A",
			"ᾈ" => "A",
			"ᾉ" => "A",
			"ᾊ" => "A",
			"ᾋ" => "A",
			"ᾌ" => "A",
			"ᾍ" => "A",
			"ᾎ" => "A",
			"ᾏ" => "A",
			"Ᾰ" => "A",
			"Ᾱ" => "A",
			"Ὰ" => "A",
			"Ά" => "A",
			"ᾼ" => "A",
			"ą" => "a",
			"æ" => "ae",
			"ბ" => "b",
			"ჩ" => "ch",
			"ჭ" => "ch",
			"დ" => "d",
			"ð" => "d",
			"Ð" => "D",
			"ძ" => "dz",
			"ἐ" => "e",
			"ἑ" => "e",
			"ἒ" => "e",
			"ἓ" => "e",
			"ἔ" => "e",
			"ἕ" => "e",
			"ὲ" => "e",
			"έ" => "e",
			"ე" => "e",
			"Ἐ" => "E",
			"Ἑ" => "E",
			"Ἒ" => "E",
			"Ἓ" => "E",
			"Ἔ" => "E",
			"Ἕ" => "E",
			"Έ" => "E",
			"Ὲ" => "E",
			"გ" => "g",
			"ღ" => "gh",
			"ჰ" => "h",
			"Ħ" => "H",
			"ἠ" => "i",
			"ἡ" => "i",
			"ἢ" => "i",
			"ἣ" => "i",
			"ἤ" => "i",
			"ἥ" => "i",
			"ἦ" => "i",
			"ἧ" => "i",
			"ᾐ" => "i",
			"ᾑ" => "i",
			"ᾒ" => "i",
			"ᾓ" => "i",
			"ᾔ" => "i",
			"ᾕ" => "i",
			"ᾖ" => "i",
			"ᾗ" => "i",
			"ὴ" => "i",
			"ή" => "i",
			"ῂ" => "i",
			"ῃ" => "i",
			"ῄ" => "i",
			"ῆ" => "i",
			"ῇ" => "i",
			"ἰ" => "i",
			"ἱ" => "i",
			"ἲ" => "i",
			"ἳ" => "i",
			"ἴ" => "i",
			"ἵ" => "i",
			"ἶ" => "i",
			"ἷ" => "i",
			"ὶ" => "i",
			"ί" => "i",
			"ῐ" => "i",
			"ῑ" => "i",
			"ῒ" => "i",
			"ΐ" => "i",
			"ῖ" => "i",
			"ῗ" => "i",
			"ი" => "i",
			"Ἠ" => "I",
			"Ἡ" => "I",
			"Ἢ" => "I",
			"Ἣ" => "I",
			"Ἤ" => "I",
			"Ἥ" => "I",
			"Ἦ" => "I",
			"Ἧ" => "I",
			"ᾘ" => "I",
			"ᾙ" => "I",
			"ᾚ" => "I",
			"ᾛ" => "I",
			"ᾜ" => "I",
			"ᾝ" => "I",
			"ᾞ" => "I",
			"ᾟ" => "I",
			"Ὴ" => "I",
			"Ή" => "I",
			"ῌ" => "I",
			"Ἰ" => "I",
			"Ἱ" => "I",
			"Ἲ" => "I",
			"Ἳ" => "I",
			"Ἴ" => "I",
			"Ἵ" => "I",
			"Ἶ" => "I",
			"Ἷ" => "I",
			"Ῐ" => "I",
			"Ῑ" => "I",
			"Ὶ" => "I",
			"Ί" => "I",
			"ĳ" => "ij",
			"Ĳ" => "IJ",
			"ჯ" => "j",
			"კ" => "k",
			"ქ" => "k",
			"ხ" => "kh",
			"ĸ" => "k",
			"ლ" => "l",
			"Ĺ" => "K",
			"Ľ" => "K",
			"Ŀ" => "K",
			"Ļ" => "K",
			"მ" => "m",
			"ნ" => "n",
			"ὀ" => "o",
			"ὁ" => "o",
			"ὂ" => "o",
			"ὃ" => "o",
			"ὄ" => "o",
			"ὅ" => "o",
			"ὸ" => "o",
			"ό" => "o",
			"ὠ" => "o",
			"ὡ" => "o",
			"ὢ" => "o",
			"ὣ" => "o",
			"ὤ" => "o",
			"ὥ" => "o",
			"ὦ" => "o",
			"ὧ" => "o",
			"ᾠ" => "o",
			"ᾡ" => "o",
			"ᾢ" => "o",
			"ᾣ" => "o",
			"ᾤ" => "o",
			"ᾥ" => "o",
			"ᾦ" => "o",
			"ᾧ" => "o",
			"ὼ" => "o",
			"ώ" => "o",
			"ῲ" => "o",
			"ῳ" => "o",
			"ῴ" => "o",
			"ῶ" => "o",
			"ῷ" => "o",
			"ო" => "o",
			"Ὀ" => "O",
			"Ὁ" => "O",
			"Ὂ" => "O",
			"Ὃ" => "O",
			"Ὄ" => "O",
			"Ὅ" => "O",
			"Ὸ" => "O",
			"Ό" => "O",
			"Ὠ" => "O",
			"Ὡ" => "O",
			"Ὢ" => "O",
			"Ὣ" => "O",
			"Ὤ" => "O",
			"Ὥ" => "O",
			"Ὦ" => "O",
			"Ὧ" => "O",
			"ᾨ" => "O",
			"ᾩ" => "O",
			"ᾪ" => "O",
			"ᾫ" => "O",
			"ᾬ" => "O",
			"ᾭ" => "O",
			"ᾮ" => "O",
			"ᾯ" => "O",
			"Ὼ" => "O",
			"Ώ" => "O",
			"ῼ" => "O",
			"Ő" => "O",
			"ø" => "o",
			"Ø" => "O",
			"ö" => "oe",
			"Ö" => "Oe",
			"პ" => "p",
			"ფ" => "p",
			"ყ" => "q",
			"ῤ" => "r",
			"ῥ" => "r",
			"რ" => "r",
			"Ῥ" => "R",
			"ŕ" => "r",
			"ř" => "r",
			"ŗ" => "r",
			"ს" => "s",
			"შ" => "sh",
			"ſ" => "ss",
			"თ" => "t",
			"ტ" => "t",
			"ც" => "ts",
			"წ" => "ts",
			"უ" => "u",
			"ü" => "u",
			"ü" => "u",
			"Ü" => "Ue",
			"ვ" => "v",
			"ὐ" => "y",
			"ὑ" => "y",
			"ὒ" => "y",
			"ὓ" => "y",
			"ὔ" => "y",
			"ὕ" => "y",
			"ὖ" => "y",
			"ὗ" => "y",
			"ὺ" => "y",
			"ύ" => "y",
			"ῠ" => "y",
			"ῡ" => "y",
			"ῢ" => "y",
			"ΰ" => "y",
			"ῦ" => "y",
			"ῧ" => "y",
			"Ὑ" => "Y",
			"Ὓ" => "Y",
			"Ὕ" => "Y",
			"Ὗ" => "Y",
			"Ῠ" => "Y",
			"Ῡ" => "Y",
			"Ὺ" => "Y",
			"Ύ" => "Y",
			"ზ" => "z",
			"ჟ" => "zh",
			"Þ" => "TH",
			"Α" => "A",
			"α" => "a",
			"Ά" => "A",
			"ά" => "a",
			"Β" => "B",
			"β" => "b",
			"Γ" => "G",
			"γ" => "g",
			"Δ" => "D",
			"δ" => "d",
			"Ε" => "E",
			"ε" => "e",
			"Έ" => "E",
			"έ" => "e",
			"Ζ" => "Z",
			"ζ" => "z",
			"Η" => "I",
			"η" => "i",
			"Ή" => "I",
			"ή" => "i",
			"Θ" => "TH",
			"θ" => "th",
			"Ι" => "I",
			"ι" => "i",
			"Ί" => "I",
			"ί" => "i",
			"Ϊ" => "I",
			"ϊ" => "i",
			"ΐ" => "i",
			"Κ" => "K",
			"κ" => "k",
			"Λ" => "L",
			"λ" => "l",
			"Μ" => "M",
			"μ" => "m",
			"Ν" => "N",
			"ν" => "n",
			"Ξ" => "KS",
			"ξ" => "ks",
			"Ο" => "O",
			"ο" => "o",
			"Ό" => "O",
			"ό" => "o",
			"Π" => "P",
			"π" => "p",
			"ρ" => "r",
			"Ρ" => "R",
			"Σ" => "S",
			"σ" => "s",
			"ς" => "s",
			"Τ" => "T",
			"τ" => "t",
			"Υ" => "Y",
			"υ" => "y",
			"Ύ" => "Y",
			"ύ" => "y",
			"Ϋ" => "Y",
			"ϋ" => "y",
			"ΰ" => "y",
			"Φ" => "F",
			"φ" => "f",
			"Χ" => "X",
			"χ" => "x",
			"Ψ" => "PS",
			"ψ" => "ps",
			"Ω" => "O",
			"ω" => "o",
			"Ώ" => "O",
			"ώ" => "o",
			"а" => "A",
			"А" => "A",
			"б" => "B",
			"Б" => "B",
			"в" => "V",
			"В" => "V",
			"г" => "G",
			"Г" => "G",
			"д" => "D",
			"Д" => "D",
			"е" => "E",
			"Е" => "E",
			"ё" => "E",
			"Ё" => "E",
			"ж" => "ZH",
			"Ж" => "ZH",
			"з" => "Z",
			"З" => "Z",
			"и" => "I",
			"И" => "I",
			"й" => "I",
			"Й" => "I",
			"к" => "K",
			"К" => "K",
			"л" => "L",
			"Л" => "L",
			"м" => "M",
			"М" => "M",
			"н" => "N",
			"Н" => "N",
			"о" => "O",
			"О" => "O",
			"п" => "P",
			"П" => "P",
			"р" => "R",
			"Р" => "R",
			"с" => "S",
			"С" => "S",
			"т" => "T",
			"Т" => "T",
			"у" => "U",
			"У" => "U",
			"ф" => "F",
			"Ф" => "F",
			"х" => "KH",
			"Х" => "KH",
			"ц" => "TS",
			"Ц" => "TS",
			"ч" => "CH",
			"Ч" => "CH",
			"ш" => "SH",
			"Ш" => "SH",
			"щ" => "SHCH",
			"Щ" => "SHCH",
			"ъ" => "",
			"Ъ" => "",
			"ы" => "Y",
			"Ы" => "Y",
			"ь" => "",
			"Ь" => "",
			"э" => "E",
			"Э" => "E",
			"ю" => "YU",
			"Ю" => "YU",
			"я" => "YA",
			"Я" => "YA",
			"א" => "A",
			"ב" => "B",
			"ג" => "G",
			"ד" => "D",
			"ה" => "Ha",
			"ו" => "V",
			"ז" => "Z",
			"ח" => "H",
			"ט" => "T",
			"י" => "I",
			"כ" => "K",
			"ך" => "H",
			"ל" => "L",
			"מ" => "M",
			"ם" => "M",
			"נ" => "N",
			"ן" => "N",
			"ס" => "S",
			"פ" => "P",
			"ף" => "F",
			"ק" => "K",
			"ר" => "R",
			"ש" => "SH",
			"ע" => "O",
			"צ" => "TZ",
			"ץ" => "TZ"
	);

	/**
	 * translates characters with diacritical marks to simple ones
	 *
	 * @param string $string
	 * @return string
	 */
	static function filter($string) {
		// strip/convert a few specific characters
		$string = strtr($string, zenphoto_seo::$specialchars);
		if (getOption('zenphoto_seo_lowercase'))
			$string = strtolower($string);
		$string = preg_replace("/\s+/", "-", $string);
		$string = preg_replace("/[^a-zA-Z0-9_.-]/", "-", $string);
		$string = str_replace(array('---', '--'), '-', $string);
		return $string;
	}

	static function js($string) {
		$xlate = array();
		foreach (zenphoto_seo::$specialchars as $from => $to) {
			if (array_key_exists($to, $xlate)) {
				$xlate[$to] .= $from;
			} else {
				$xlate[$to] = $from;
			}
		}
		$js = '
			function seoFriendlyJS(fname) {
				fname=fname.trim();
				fname=fname.replace(/\s+\.\s*/,".");
			';

		foreach ($xlate as $to => $from) {
			$js .= "				fname = fname.replace(/[" . $from . "]/g, '" . $to . "');\n";
		}

		if (getOption('zenphoto_seo_lowercase')) {
			$js .= "				fname = fname.toLowerCase();\n";
		}
		$js .= "
				fname = fname.replace(/\s+/g, '-');
				fname = fname.replace(/[^a-zA-Z0-9_.-]/g, '-');
				fname = fname.replace(/--*/g, '-');
				return fname;
			}\n";
		return $js;
	}

}

?>
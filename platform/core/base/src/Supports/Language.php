<?php

namespace Guestcms\Base\Supports;

use Guestcms\Base\Facades\BaseHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class Language
{
    /**
     * The list of flags
     *
     * for each flag:
     * the key is the flag file name (without the extension)
     * the value is the Country name
     */
    protected static array $flags = [
        'ad' => 'Andorra',
        'ae' => 'United Arab Emirates',
        'af' => 'Afghanistan',
        'ag' => 'Antigua and Barbuda',
        'ai' => 'Anguilla',
        'al' => 'Albania',
        'am' => 'Armenia',
        'ao' => 'Angola',
        'ar' => 'Argentina',
        'as' => 'American Samoa',
        'at' => 'Austria',
        'au' => 'Australia',
        'aw' => 'Aruba',
        'ax' => 'Åland Islands',
        'az' => 'Azerbaijan',
        'ba' => 'Bosnia and Herzegovina',
        'bb' => 'Barbados',
        'bd' => 'Bangladesh',
        'be' => 'Belgium',
        'bf' => 'Burkina Faso',
        'bg' => 'Bulgaria',
        'bh' => 'Bahrain',
        'bi' => 'Burundi',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bn' => 'Brunei',
        'bo' => 'Bolivia',
        'br' => 'Brazil',
        'bs' => 'Bahamas',
        'bt' => 'Bhutan',
        'bw' => 'Botswana',
        'by' => 'Belarus',
        'bz' => 'Belize',
        'ca' => 'Canada',
        'ca_ES' => 'Catalonia',
        'cc' => 'Cocos',
        'cd' => 'Democratic Republic of the Congo',
        'cf' => 'Central African Republic',
        'cg' => 'Congo',
        'ch' => 'Switzerland',
        'ci' => 'Ivory Coast',
        'ck' => 'Cook Islands',
        'cl' => 'Chile',
        'cm' => 'Cameroon',
        'cn' => 'China',
        'co' => 'Colombia',
        'cr' => 'Costa Rica',
        'cu' => 'Cuba',
        'cv' => 'Cape Verde',
        'cx' => 'Christmas Island',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'de' => 'Germany',
        'dj' => 'Djibouti',
        'dk' => 'Denmark',
        'dm' => 'Dominica',
        'do' => 'Dominican Republic',
        'dz' => 'Algeria',
        'ec' => 'Ecuador',
        'ee' => 'Estonia',
        'eg' => 'Egypt',
        'eh' => 'Western Sahara',
        'gb-eng' => 'England',
        'er' => 'Eritrea',
        'es' => 'Spain',
        'et' => 'Ethiopia',
        'fi' => 'Finland',
        'fj' => 'Fiji',
        'fk' => 'Falkland Islands',
        'fm' => 'Micronesia',
        'fo' => 'Faroe Islands',
        'fr' => 'France',
        'ga' => 'Gabon',
        'gb' => 'United Kingdom',
        'gd' => 'Grenada',
        'ge' => 'Georgia',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gl' => 'Greenland',
        'gm' => 'Gambia',
        'gn' => 'Guinea',
        'gp' => 'Guadeloupe',
        'gq' => 'Equatorial Guinea',
        'gr' => 'Greece',
        'gs' => 'South Georgia and the South Sandwich Islands',
        'gt' => 'Guatemala',
        'gu' => 'Guam',
        'gw' => 'Guinea-Bissau',
        'gy' => 'Guyana',
        'hk' => 'Hong Kong',
        'hm' => 'Heard Island and McDonald Islands',
        'hn' => 'Honduras',
        'hr' => 'Croatia',
        'ht' => 'Haiti',
        'hu' => 'Hungary',
        'id' => 'Indonesia',
        'ie' => 'Republic of Ireland',
        'il' => 'Israel',
        'in' => 'India',
        'io' => 'British Indian Ocean Territory',
        'iq' => 'Iraq',
        'ir' => 'Iran',
        'is' => 'Iceland',
        'it' => 'Italy',
        'jm' => 'Jamaica',
        'jo' => 'Jordan',
        'jp' => 'Japan',
        'ke' => 'Kenya',
        'kg' => 'Kyrgyzstan',
        'kh' => 'Cambodia',
        'ki' => 'Kiribati',
        'km' => 'Comoros',
        'kn' => 'Saint Kitts and Nevis',
        'kp' => 'North Korea',
        'kr' => 'South Korea',
        'kw' => 'Kuwait',
        'ky' => 'Cayman Islands',
        'kz' => 'Kazakhstan',
        'la' => 'Laos',
        'lb' => 'Lebanon',
        'lc' => 'Saint Lucia',
        'li' => 'Liechtenstein',
        'lk' => 'Sri Lanka',
        'lr' => 'Liberia',
        'ls' => 'Lesotho',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'lv' => 'Latvia',
        'ly' => 'Libya',
        'ma' => 'Morocco',
        'mc' => 'Monaco',
        'md' => 'Moldova',
        'me' => 'Montenegro',
        'mg' => 'Madagascar',
        'mh' => 'Marshall Islands',
        'mk' => 'Macedonia',
        'ml' => 'Mali',
        'mm' => 'Myanmar',
        'mn' => 'Mongolia',
        'mo' => 'Macao',
        'mp' => 'Northern Mariana Islands',
        'mq' => 'Martinique',
        'mr' => 'Mauritania',
        'ms' => 'Montserrat',
        'mt' => 'Malta',
        'mu' => 'Mauritius',
        'mv' => 'Maldives',
        'mw' => 'Malawi',
        'mx' => 'Mexico',
        'my' => 'Malaysia',
        'mz' => 'Mozambique',
        'na' => 'Namibia',
        'nc' => 'New Caledonia',
        'ne' => 'Niger',
        'nf' => 'Norfolk Island',
        'ng' => 'Nigeria',
        'ni' => 'Nicaragua',
        'nl' => 'Netherlands',
        'no' => 'Norway',
        'np' => 'Nepal',
        'nr' => 'Nauru',
        'nu' => 'Niue',
        'nz' => 'New Zealand',
        'om' => 'Oman',
        'pa' => 'Panama',
        'pe' => 'Peru',
        'pf' => 'French Polynesia',
        'pg' => 'Papua New Guinea',
        'ph' => 'Philippines',
        'pk' => 'Pakistan',
        'pl' => 'Poland',
        'pm' => 'Saint Pierre and Miquelon',
        'pn' => 'Pitcairn',
        'pr' => 'Puerto Rico',
        'ps' => 'Palestinian Territory',
        'pt' => 'Portugal',
        'pw' => 'Belau',
        'py' => 'Paraguay',
        'qa' => 'Qatar',
        'ro' => 'Romania',
        'rs' => 'Serbia',
        'ru' => 'Russia',
        'rw' => 'Rwanda',
        'sa' => 'Saudi Arabia',
        'sb' => 'Solomon Islands',
        'sc' => 'Seychelles',
        'gb-sct' => 'Scotland',
        'sd' => 'Sudan',
        'se' => 'Sweden',
        'sg' => 'Singapore',
        'sh' => 'Saint Helena',
        'si' => 'Slovenia',
        'sk' => 'Slovakia',
        'sl' => 'Sierra Leone',
        'sm' => 'San Marino',
        'sn' => 'Senegal',
        'so' => 'Somalia',
        'sr' => 'Suriname',
        'ss' => 'South Sudan',
        'st' => 'São Tomé and Príncipe',
        'sv' => 'El Salvador',
        'sy' => 'Syria',
        'sz' => 'Swaziland',
        'tc' => 'Turks and Caicos Islands',
        'td' => 'Chad',
        'tf' => 'French Southern Territories',
        'tg' => 'Togo',
        'th' => 'Thailand',
        'tj' => 'Tajikistan',
        'tk' => 'Tokelau',
        'tl' => 'Timor-Leste',
        'tm' => 'Turkmenistan',
        'tn' => 'Tunisia',
        'to' => 'Tonga',
        'tr' => 'Turkey',
        'tt' => 'Trinidad and Tobago',
        'tv' => 'Tuvalu',
        'tw' => 'Taiwan',
        'tz' => 'Tanzania',
        'ua' => 'Ukraine',
        'ug' => 'Uganda',
        'us' => 'United States',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'va' => 'Vatican',
        'vc' => 'Saint Vincent and the Grenadines',
        've' => 'Venezuela',
        'vg' => 'British Virgin Islands',
        'vi' => 'United States Virgin Islands',
        'vn' => 'Vietnam',
        'vu' => 'Vanuatu',
        'gb-wls' => 'Wales',
        'wf' => 'Wallis and Futuna',
        'ws' => 'Western Samoa',
        'ye' => 'Yemen',
        'yt' => 'Mayotte',
        'za' => 'South Africa',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe',
    ];

    /**
     * The list of predefined languages
     *
     * for each language:
     * [0] => ISO 639-1 language code
     * [1] => Laravel locale
     * [2] => name
     * [3] => text direction
     * [4] => flag code
     */
    protected static array $languages = [
        'af' => ['af', 'af', 'Afrikaans', 'ltr', 'za'],
        'ar' => ['ar', 'ar', 'العربية', 'rtl', 'ar'],
        'ary' => ['ar', 'ary', 'العربية المغربية', 'rtl', 'ma'],
        'az' => ['az', 'az', 'Azərbaycan', 'ltr', 'az'],
        'azb' => ['az', 'azb', 'گؤنئی آذربایجان', 'rtl', 'az'],
        'bel' => ['be', 'bel', 'Беларуская мова', 'ltr', 'by'],
        'bg_BG' => ['bg', 'bg_BG', 'български', 'ltr', 'bg'],
        'bn_BD' => ['bn', 'bn_BD', 'বাংলা', 'ltr', 'bd'],
        'bo' => ['bo', 'bo', 'བོད་སྐད', 'ltr', 'tibet'],
        'bs_BA' => ['bs', 'bs_BA', 'Bosanski', 'ltr', 'ba'],
        'ca' => ['ca', 'ca_ES', 'Catalan', 'ltr', 'es'],
        'ceb' => ['ceb', 'ceb', 'Cebuano', 'ltr', 'ph'],
        'cs_CZ' => ['cs', 'cs_CZ', 'Čeština', 'ltr', 'cz'],
        'cy' => ['cy', 'cy', 'Cymraeg', 'ltr', 'gb-wls'],
        'da_DK' => ['da', 'da_DK', 'Dansk', 'ltr', 'dk'],
        'de_CH' => ['de', 'de_CH', 'Deutsch', 'ltr', 'ch'],
        'de_CH_informal' => ['de', 'de_CH_informal', 'Deutsch', 'ltr', 'ch'],
        'de_DE' => ['de', 'de_DE', 'Deutsch', 'ltr', 'de'],
        'de_DE_formal' => ['de', 'de_DE_formal', 'Deutsch', 'ltr', 'de'],
        'el' => ['el', 'el', 'Ελληνικά', 'ltr', 'gr'],
        'en_US' => ['en', 'en_US', 'English', 'ltr', 'us'],
        'en_AU' => ['en', 'en_AU', 'English', 'ltr', 'au'],
        'en_CA' => ['en', 'en_CA', 'English', 'ltr', 'ca'],
        'en_GB' => ['en', 'en_GB', 'English', 'ltr', 'gb'],
        'en_NZ' => ['en', 'en_NZ', 'English', 'ltr', 'nz'],
        'en_ZA' => ['en', 'en_ZA', 'English', 'ltr', 'za'],
        'es_AR' => ['es', 'es_AR', 'Español', 'ltr', 'ar'],
        'es_CL' => ['es', 'es_CL', 'Español', 'ltr', 'cl'],
        'es_CO' => ['es', 'es_CO', 'Español', 'ltr', 'co'],
        'es_ES' => ['es', 'es_ES', 'Español', 'ltr', 'es'],
        'es_GT' => ['es', 'es_GT', 'Español', 'ltr', 'gt'],
        'es_MX' => ['es', 'es_MX', 'Español', 'ltr', 'mx'],
        'es_PE' => ['es', 'es_PE', 'Español', 'ltr', 'pe'],
        'es_VE' => ['es', 'es_VE', 'Español', 'ltr', 've'],
        'et' => ['et', 'et', 'Eesti', 'ltr', 'ee'],
        'eu' => ['eu', 'eu', 'Euskara', 'ltr', 'fr'],
        'fa_AF' => ['fa', 'fa_AF', 'فارسی', 'rtl', 'af'],
        'fa_IR' => ['fa', 'fa_IR', 'فارسی', 'rtl', 'ir'],
        'fi' => ['fi', 'fi', 'Suomi', 'ltr', 'fi'],
        'fo' => ['fo', 'fo', 'Føroyskt', 'ltr', 'fo'],
        'fr_BE' => ['fr', 'fr_BE', 'Français', 'ltr', 'be'],
        'fr_FR' => ['fr', 'fr_FR', 'Français', 'ltr', 'fr'],
        'fy' => ['fy', 'fy', 'Frysk', 'ltr', 'nl'],
        'gd' => ['gd', 'gd', 'Gàidhlig', 'ltr', 'gb-sct'],
        'gl_ES' => ['gl', 'gl_ES', 'Galego', 'ltr', 'gl'],
        'gu' => ['gu', 'gu', 'ગુજરાતી', 'ltr', 'in'],
        'haz' => ['haz', 'haz', 'هزاره گی', 'rtl', 'af'],
        'he_IL' => ['he', 'he_IL', 'עברית', 'rtl', 'il'],
        'hi_IN' => ['hi', 'hi_IN', 'हिन्दी', 'ltr', 'in'],
        'hr' => ['hr', 'hr', 'Hrvatski', 'ltr', 'hr'],
        'ht' => ['ht', 'ht', 'Kreyòl Ayisyen', 'ltr', 'ht'],
        'hu_HU' => ['hu', 'hu_HU', 'Magyar', 'ltr', 'hu'],
        'hy' => ['hy', 'hy', 'Հայերեն', 'ltr', 'am'],
        'id_ID' => ['id', 'id_ID', 'Bahasa Indonesia', 'ltr', 'id'],
        'is_IS' => ['is', 'is_IS', 'Íslenska', 'ltr', 'is'],
        'it_IT' => ['it', 'it_IT', 'Italiano', 'ltr', 'it'],
        'ja' => ['ja', 'ja', '日本語', 'ltr', 'jp'],
        'jv_ID' => ['jv', 'jv_ID', 'Basa Jawa', 'ltr', 'id'],
        'ka_GE' => ['ka', 'ka_GE', 'ქართული', 'ltr', 'ge'],
        'kk' => ['kk', 'kk', 'Қазақ тілі', 'ltr', 'kz'],
        'kh' => ['kh', 'kh', 'Cambodia', 'ltr', 'kh'],
        'ko_KR' => ['ko', 'ko_KR', '한국어', 'ltr', 'kr'],
        'ky_KG' => ['ky', 'ky_KG', 'Кыргызча', 'ltr', 'kg'],
        'ckb' => ['ku', 'ckb', 'کوردی', 'rtl', 'kurdistan'],
        'lo' => ['lo', 'lo', 'ພາສາລາວ', 'ltr', 'la'],
        'lt_LT' => ['lt', 'lt_LT', 'Lietuviškai', 'ltr', 'lt'],
        'lv' => ['lv', 'lv', 'Latviešu valoda', 'ltr', 'lv'],
        'mk_MK' => ['mk', 'mk_MK', 'македонски јазик', 'ltr', 'mk'],
        'mn' => ['mn', 'mn', 'Монгол хэл', 'ltr', 'mn'],
        'mr' => ['mr', 'mr', 'मराठी', 'ltr', 'in'],
        'ms_MY' => ['ms', 'ms_MY', 'Bahasa Melayu', 'ltr', 'my'],
        'my_MM' => ['my', 'my_MM', 'ဗမာစာ', 'ltr', 'mm'],
        'mv' => ['mv', 'mv', 'Maldives', 'rtl', 'mv'],
        'nb_NO' => ['nb', 'nb_NO', 'Norsk Bokmål', 'ltr', 'no'],
        'ne_NP' => ['ne', 'ne_NP', 'नेपाली', 'ltr', 'np'],
        'nl_NL' => ['nl', 'nl_NL', 'Nederlands', 'ltr', 'nl'],
        'nl_NL_formal' => ['nl', 'nl_NL_formal', 'Nederlands', 'ltr', 'nl'],
        'nn_NO' => ['nn', 'nn_NO', 'Norsk Nynorsk', 'ltr', 'no'],
        'pl_PL' => ['pl', 'pl_PL', 'Polski', 'ltr', 'pl'],
        'ps' => ['ps', 'ps', 'پښتو', 'rtl', 'af'],
        'pt_BR' => ['pt', 'pt_BR', 'Português', 'ltr', 'br'],
        'pt_PT' => ['pt', 'pt_PT', 'Português', 'ltr', 'pt'],
        'ro_RO' => ['ro', 'ro_RO', 'Română', 'ltr', 'ro'],
        'ru_RU' => ['ru', 'ru_RU', 'Русский', 'ltr', 'ru'],
        'si_LK' => ['si', 'si_LK', 'සිංහල', 'ltr', 'lk'],
        'sk_SK' => ['sk', 'sk_SK', 'Slovenčina', 'ltr', 'sk'],
        'sl_SI' => ['sl', 'sl_SI', 'Slovenščina', 'ltr', 'si'],
        'so_SO' => ['so', 'so_SO', 'Af-Soomaali', 'ltr', 'so'],
        'sq' => ['sq', 'sq', 'Shqip', 'ltr', 'al'],
        'sr_RS' => ['sr', 'sr_RS', 'Српски језик', 'ltr', 'rs'],
        'su_ID' => ['su', 'su_ID', 'Basa Sunda', 'ltr', 'id'],
        'sv_SE' => ['sv', 'sv_SE', 'Svenska', 'ltr', 'se'],
        'szl' => ['szl', 'szl', 'Ślōnskŏ gŏdka', 'ltr', 'pl'],
        'sw' => ['sw', 'sw', 'Swahili', 'ltr', 'tz'],
        'ta_LK' => ['ta', 'ta_LK', 'தமிழ்', 'ltr', 'lk'],
        'th' => ['th', 'th', 'ไทย', 'ltr', 'th'],
        'tl' => ['tl', 'tl', 'Tagalog', 'ltr', 'ph'],
        'tr_TR' => ['tr', 'tr_TR', 'Türkçe', 'ltr', 'tr'],
        'ug_CN' => ['ug', 'ug_CN', 'Uyƣurqə', 'ltr', 'cn'],
        'uk' => ['uk', 'uk', 'Українська', 'ltr', 'ua'],
        'ur' => ['ur', 'ur', 'اردو', 'rtl', 'pk'],
        'uz_UZ' => ['uz', 'uz_UZ', 'Oʻzbek', 'ltr', 'uz'],
        'vi' => ['vi', 'vi', 'Tiếng Việt', 'ltr', 'vn'],
        'zh_CN' => ['zh', 'zh_CN', '中文 (中国)', 'ltr', 'cn'],
        'zh_HK' => ['zh', 'zh_HK', '中文 (香港)', 'ltr', 'hk'],
        'zh_TW' => ['zh', 'zh_TW', '中文 (台灣)', 'ltr', 'tw'],
        'tg' => ['tg', 'tg', 'Tajik', 'ltr', 'tj'],
    ];

    public static function getListLanguageFlags(): array
    {
        return self::$flags;
    }

    public static function getAvailableLocales(bool $original = false): array
    {
        $languages = [];
        $locales = BaseHelper::scanFolder(lang_path());
        if (in_array('vendor', $locales)) {
            $locales = array_merge($locales, BaseHelper::scanFolder(lang_path('vendor')));
        }

        foreach ($locales as $locale) {
            if ($locale === 'vendor') {
                continue;
            }

            foreach (Language::getListLanguages() as $key => $language) {
                if (in_array($key, [$locale, str_replace('-', '_', $locale)]) ||
                    in_array($language[1], [$locale, str_replace('-', '_', $locale)])
                ) {
                    $languages[$locale] = [
                        'locale' => $locale,
                        'code' => $language[1],
                        'name' => $language[2],
                        'flag' => $language[4],
                        'is_rtl' => $language[3] === 'rtl',
                    ];

                    break;
                }

                if (! array_key_exists($locale, $languages) &&
                    in_array($language[0], [$locale, str_replace('-', '_', $locale)])) {
                    $languages[$locale] = [
                        'locale' => $locale,
                        'code' => $language[1],
                        'name' => $language[2],
                        'flag' => $language[4],
                        'is_rtl' => $language[3] === 'rtl',
                    ];
                }
            }

            if (! array_key_exists($locale, $languages) && File::isDirectory(lang_path($locale))) {
                $languages[$locale] = [
                    'locale' => $locale,
                    'code' => $locale,
                    'name' => $locale,
                    'flag' => $locale,
                    'is_rtl' => Arr::get($languages, "$locale.3") === 'rtl',
                ];
            }
        }

        if ($original) {
            return $languages;
        }

        return apply_filters('core_available_locales', $languages);
    }

    public static function getListLanguages(): array
    {
        return self::$languages;
    }

    public static function getDefaultLanguage(): array
    {
        return apply_filters('core_default_language', [
            'locale' => 'en',
            'code' => 'en_US',
            'name' => 'English',
            'flag' => 'us',
            'is_rtl' => false,
        ]);
    }

    public static function getLocales(): array
    {
        $locales = collect(static::getListLanguages())->pluck('2', '0')->unique()->all();

        $locales = [
            ...$locales,
            'de_CH' => 'Deutsch (Schweiz)',
            'pt_BR' => 'Português (Brasil)',
            'sr_Cyrl' => 'Српски (ћирилица)',
            'sr_Latn' => 'Srpski (latinica)',
            'sr_Latn_ME' => 'Srpski (latinica, Crna Gora)',
            'uz_Cyrl' => 'Ўзбек (Ўзбекистон)',
            'uz_Latn' => 'O‘zbek',
            'zh_CN' => '中文 (中国)',
            'zh_TW' => '中文 (台灣)',
            'zh_HK' => '中文 (香港)',
        ];

        ksort($locales);

        return $locales;
    }

    public static function getLocaleKeys(): array
    {
        $locales = array_unique(array_keys(static::getLocales()));

        return apply_filters('core_locales', $locales);
    }

    public static function getLanguageCodes(): array
    {
        return collect(static::getListLanguages())->pluck('1')->unique()->all();
    }

    public static function getCurrentLocale(): array
    {
        $locale = static::getDefaultLanguage();

        if (array_key_exists($currentLocale = App::getLocale(), $availableLocales = static::getAvailableLocales())) {
            return Arr::get($availableLocales, $currentLocale, $locale);
        }

        return $locale;
    }
}

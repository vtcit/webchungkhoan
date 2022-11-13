<?php

namespace common\helpers;

use yii\helpers\Inflector;

/**
 * 
 */

class VusInflector extends Inflector
{
    public static $foreign_characters = [
        '/ä|æ|ǽ/' => 'ae',
        '/ö|œ/' => 'oe',
        '/ü/' => 'ue',
        '/Ä/' => 'Ae',
        '/Ü/' => 'Ue',
        '/Ö/' => 'Oe',
        '/À|Á|Â|Ã|Ä|Å|Ǻ|Ā|Ă|Ą|Ǎ|Ả|Ạ|Ắ|Ằ|Ẳ|Ẵ|Ặ|Ấ|Ầ|Ẩ|Ẫ|Ậ/' => 'A',
        '/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ª|ả|ạ|ắ|ằ|ẳ|ẵ|ặ|ấ|ầ|ẩ|ẫ|ậ/' => 'a',
        '/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
        '/ç|ć|ĉ|ċ|č/' => 'c',
        '/Ð|Ď|Đ/' => 'D',
        '/ð|ď|đ/' => 'd',
        '/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Ẻ|Ẽ|Ẹ|Ế|Ề|Ể|Ễ|Ệ/' => 'E',
        '/è|é|ê|ë|ē|ĕ|ė|ę|ě|ẻ|ẽ|ẹ|ế|ề|ể|ễ|ệ/' => 'e',
        '/Ĝ|Ğ|Ġ|Ģ/' => 'G',
        '/ĝ|ğ|ġ|ģ/' => 'g',
        '/Ĥ|Ħ/' => 'H',
        '/ĥ|ħ/' => 'h',
        '/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Ỉ|Ĩ|Ị/' => 'I',
        '/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ı|ỉ|ĩ|ị/' => 'i',
        '/Ĵ/' => 'J',
        '/ĵ/' => 'j',
        '/Ķ/' => 'K',
        '/ķ/' => 'k',
        '/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
        '/ĺ|ļ|ľ|ŀ|ł/' => 'l',
        '/Ñ|Ń|Ņ|Ň/' => 'N',
        '/ñ|ń|ņ|ň|ŉ/' => 'n',
        '/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ø|Ǿ|Ỏ|Õ|Ọ|Ố|Ồ|Ổ|Ỗ|Ộ|Ớ|Ờ|Ở|Ỡ|Ợ/' => 'O',
        '/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ø|ǿ|º|ỏ|õ|ọ|ố|ồ|ổ|ỗ|ộ|ớ|ờ|ở|ỡ|ợ/' => 'o',
        '/Ŕ|Ŗ|Ř/' => 'R',
        '/ŕ|ŗ|ř/' => 'r',
        '/Ś|Ŝ|Ş|Š/' => 'S',
        '/ś|ŝ|ş|š|ſ/' => 's',
        '/Ţ|Ť|Ŧ/' => 'T',
        '/ţ|ť|ŧ/' => 't',
        '/Ù|Ú|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ|Ủ|Ũ|Ụ|Ứ|Ừ|Ử|Ữ|Ự/' => 'U',
        '/ù|ú|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ|ủ|ũ|ụ|ứ|ừ|ử|ữ|ự/' => 'u',
        '/Ý|Ÿ|Ŷ|Ỳ|Ỷ|Ỹ|Ỵ/' => 'Y',
        '/ý|ÿ|ŷ|ỳ|ỷ|ỹ|ỵ/' => 'y',
        '/Ŵ/' => 'W',
        '/ŵ/' => 'w',
        '/Ź|Ż|Ž/' => 'Z',
        '/ź|ż|ž/' => 'z',
        '/Æ|Ǽ/' => 'AE',
        '/æ|ǽ/' => 'ae',
        '/ß/'=> 'ss',
        '/Ĳ/' => 'IJ',
        '/ĳ/' => 'ij',
        '/Œ/' => 'OE',
        '/œ/' => 'oe',
        '/Ƒ/' => 'F',
        '/ƒ/' => 'f'
    ];

    /**
     * VusInflector: Vietnamese Slug
     *
     * Returns a string with all spaces converted to given replacement,
     * non word characters removed and the rest of characters transliterated.
     *
     * If intl extension isn't available uses fallback that converts latin characters only
     * and removes the rest. You may customize characters map via $transliteration property
     * of the helper.
     *
     * @param string $string An arbitrary string to convert
     * @param string $replacement The replacement to use for spaces
     * @param bool $lowercase whether to return the string in lowercase or not. Defaults to `true`.
     * @return string The converted string.
     */

    public static function _____slug($string, $replacement = '-', $lowercase = true)
    {
        // VusInflector Addition //$transliteration
        $string = preg_replace(array_keys(static::$foreign_characters), array_values(static::$foreign_characters), $string);

        return parent::slug($string, $replacement, $lowercase);
    }

    /**
     * VusInflector: Vietnamese Slug
     *
     * Returns transliterated version of a string.
     *
     * If intl extension isn't available uses fallback that converts latin characters only
     * and removes the rest. You may customize characters map via $transliteration property
     * of the helper.
     *
     * @param string $string input string
     * @param string|\Transliterator $transliterator either a [[\Transliterator]] or a string
     * from which a [[\Transliterator]] can be built.
     * @return string
     * @since 2.0.7 this method is public.
     */
    public static function transliterate($string, $transliterator = null)
    {
        if (static::hasIntl()) {
            if ($transliterator === null) {
                $transliterator = static::$transliterator;
            }

            return transliterator_transliterate($transliterator, $string);
        }
        //return strtr($string, static::$transliteration);
        // VusInflector Addition //$transliteration
        return preg_replace(array_keys(static::$foreign_characters), array_values(static::$foreign_characters), $string);
    }
}
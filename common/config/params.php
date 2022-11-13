<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'user.passwordResetTokenExpire' => 3600,
    'media.folder' => '/upload',
    'media.extensions' => 'png, jpg, jpeg, gif',
    'media.maxSize' => 1*(1024*1024), /* 5Mb */
    'media.maxFiles' => 2,
    'media.thumbs' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ],
        'medium' => [
            'width' => 420,
            'height' => 420,
            'crop' => false
        ],
        'large' => [
            'width' => 1024,
            'height' => 1024,
            'crop' => false
        ],
    ],
    'page_type' => [
        'service' => [
            'name' => 'service',
            'label' => 'Service',
            'attributes' => [
                /* groups attribute [] */
                [
                    'label' => false,
                    /* items attribute [] */
                    'items' => [
                        'include' => [
                            'name' => 'include',
                            'label' => 'Include',
                            'type' => 'editor',
                        ],
                        'price' => [
                            'name' => 'price',
                            'label' => 'Price',
                            'type' => 'number',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'post_type' => [
    ],
    'category_type' => [
    ],
    'foreign_characters' => [
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
    ],
];

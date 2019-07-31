<?php

namespace App\Helpers\DocumentContent\Util;

class Cipher {

    public static $setLetters=[
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',

    ];

    public static function decode($index) {
        if ($index < 1) {
            return '';
        } else if ($index <= count(static::$setLetters)) {
            return static::$setLetters[$index - 1];
        } else if ($index > count(static::$setLetters)) {

            $letters = static::$setLetters;

            // Count the occurrence of the letter 
            $occurrence = Math.round($index / letters.length);
            // Get the letter $index
            $letter_index = $index - (letters.length * Math.floor($index / letters.length));
            $letter = '';

            for ($i = 0; $i < occurrence; $i++) {
                $letter .= $letters[$letter_index - 1];
            }

            return $letter;
        }
    }
}

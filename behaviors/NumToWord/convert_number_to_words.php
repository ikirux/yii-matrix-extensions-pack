<?php

/**
 * Fork de http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php/
 **/

function convert_number_to_words($number, $suffix = '') {
    $conjunction = ' y ';
    $negative    = 'menos ';
    $decimal     = ' punto ';
    $dictionary  = [
        0                   => 'cero',
        1                   => 'uno',
        2                   => 'dos',
        3                   => 'tres',
        4                   => 'cuatro',
        5                   => 'cinco',
        6                   => 'seis',
        7                   => 'siete',
        8                   => 'ocho',
        9                   => 'nueve',
        10                  => 'diez',
        11                  => 'once',
        12                  => 'doce',
        13                  => 'trece',
        14                  => 'catorce',
        15                  => 'quince',
        16                  => 'dieciséis',
        17                  => 'diecisiete',
        18                  => 'dieciocho',
        19                  => 'diecinueve',
        20                  => 'veinte',      
        21                  => 'veintiuno',
        22                  => 'veintidos',
        23                  => 'veintitres',
        24                  => 'veinticutaro',
        25                  => 'veinticinco',
        26                  => 'veintiseis',
        27                  => 'veintisiete',
        28                  => 'veintiocho',
        29                  => 'veintinueve',
        30                  => 'treinta',
        40                  => 'cuarenta',
        50                  => 'cincuenta',
        60                  => 'sesenta',
        70                  => 'setenta',
        80                  => 'ochenta',
        90                  => 'noventa',
        100                 => 'cien',
        200                 => 'docientos',
        300                 => 'trecientos',
        400                 => 'cuatrocientos',
        500                 => 'quinientos',
        600                 => 'seiscientos',
        700                 => 'setecientos',
        800                 => 'ochocientos',
        900                 => 'novecientos',
        1000                => 'mil',
        1000000             => 'mill',
        1000000000          => 'bill',
        1000000000000       => 'trill',
        1000000000000000    => 'quadrill',
        1000000000000000000 => 'quintill',
    ];
   
    if (!is_numeric($number)) {
        return false;
    }
   
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
   
    $string = $fraction = null;
   
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
   
    switch (true) {
        case $number < 31 || $number == 100:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $conjunction . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = ((int) ($number / 100)) * 100;
            $remainder = $number % 100;
            $string = $hundreds == 100 ? $dictionary[$hundreds] . 'to' . $suffix : $dictionary[$hundreds] . $suffix;
            if ($remainder) {
                $string .=  ' ' . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
                                //echo "es $baseUnit $numBaseUnits $remainder <br />";
            // Excepcion para mil
            if ($baseUnit == 1000 && $numBaseUnits == 1) {
                $string = $dictionary[$baseUnit]; 
            } else if ($baseUnit == 1000 && $numBaseUnits != 1) {
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit]; 
            } else if ($baseUnit > 1000 && $numBaseUnits == 1) { 
                $string = 'un ' . $dictionary[$baseUnit] . 'ón';                    
            } else if ($baseUnit > 1000 && $baseUnit < 100000 && $numBaseUnits != 1) { 
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit] . 'ones';
            } else if ($baseUnit >= 1000000 && $numBaseUnits != 1) {
                $suffix = $numBaseUnits < 200 ? 'to' : '';
                $string = convert_number_to_words($numBaseUnits, $suffix) . ' ' . $dictionary[$baseUnit] . 'ones';
            } 
            
            if ($remainder) {
                $string .= ' ' . convert_number_to_words($remainder);
            }
            break;
    }
   
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $string .= convert_number_to_words($fraction);
    }
   
    return $string;
} 

<?php

require 'convert_number_to_words.php';

class NumToWordsBehavior extends CBehavior
{
    public function numToWords($number, $suffix = '')
    {
        return convert_number_to_words($number, $suffix);
    }
}
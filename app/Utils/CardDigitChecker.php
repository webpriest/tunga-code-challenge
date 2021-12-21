<?php

namespace App\Utils;

class CardDigitChecker 
{
    /**
     * Checks for 3 identical digits
     * 
     * @return boolean
     */
    public static function identicalDigits($creditCardNumber)
    {
        // Splits credit card digits into an array
        $digits = str_split($creditCardNumber);
        $length = count($digits);

        // Iterates through each of the digits and compares with the next digit
        for($i = 0; $i < $length; $i++) {
            $digit_count = 1;
            for($j = $i+1; $j < $length; $j++) {
                if($digits[$i] === $digits[$j] && ($j < $length)){
                    $digit_count++;   
                }
                
                // If 3 consecutive identical digits found, return true
                if(($j+1 < $length) && $digits[$i] === $digits[$j+1]) {
                    $digit_count++;
                    if($digit_count === 3) {
                        return true;
                    } 
                }
            }
        }

        return false;
    }
}
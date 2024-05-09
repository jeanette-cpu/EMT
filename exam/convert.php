<?php

$input_no = $_POST['input_no'];
$converted_no = "";
$decimal_index = strpos($input_no, ".");

if($decimal_index != 0){
    $whole_no = convertNumberToWords(substr($input_no, 0, $decimal_index), "w");
    if($whole_no != "invalid") {
        $converted_no .= $whole_no."dollars ";
    } else {
        $converted_no = $whole_no;
    }
    $decimal_no = convertNumberToWords(substr($input_no, $decimal_index + 1), "d");
    if($decimal_no != "") {
        if($decimal_no != "invalid")
            $converted_no .= "and ".$decimal_no."cents";
        else 
            $converted_no = $decimal_no;
    } else { 
        $converted_no .= $decimal_no;
    }
} else {
    $converted_no = convertNumberToWords($input_no, "w");
    $converted_no .= "dollars";
}

echo strtoupper($converted_no);

function convertNumberToWords($input_no, $type) {
    $array_no = array_reverse(str_split($input_no));
    $count_array = count($array_no);
    $word_no = "";

    if(($count_array > 2 && $type == "d") || ($count_array > 9 && $type == "w"))
        return "invalid";

    for($i = $count_array - 1; $i >= 0; $i--){
        $word_no .= getNumberWords($array_no, $i);
        if($array_no[$i] == 1 && ($i == 1 || $i == 4 || $i == 7)) {
            $i--;
        }
    }

    return $word_no;
}

function getNumberWords($array_no, $pos) {
    /*
        limit: hundred million and two decimal place only
        pos 0, 2, 3, 5, 6, 8 = ones and hundreds
        pos 1, 4, 7 = tens
    */
    $num_to_word = "";
    if($pos == 1 || $pos == 4 || $pos == 7) {
        if($array_no[$pos] == 1) {
            switch($array_no[$pos-1]) {
                case 0: $num_to_word = "ten "; break;
                case 1: $num_to_word = "eleven "; break;
                case 2: $num_to_word = "twelve "; break;
                case 3: $num_to_word = "thirteen "; break;
                case 4: $num_to_word = "fourteen "; break;
                case 5: $num_to_word = "fifteen "; break;
                case 6: $num_to_word = "sixteen "; break;
                case 7: $num_to_word = "seventeen "; break;
                case 8: $num_to_word = "eighteen "; break;
                case 9: $num_to_word = "nineteen "; break;
            }
        } else {
            switch($array_no[$pos]) {
                case 0: $num_to_word = ""; break;
                case 2: $num_to_word = "twenty "; break;
                case 3: $num_to_word = "thirty "; break;
                case 4: $num_to_word = "forty "; break;
                case 5: $num_to_word = "fifty "; break;
                case 6: $num_to_word = "sixty "; break;
                case 7: $num_to_word = "seventy "; break;
                case 8: $num_to_word = "eighty "; break;
                case 9: $num_to_word = "ninety "; break;
            }
        }
    } else {
        switch($array_no[$pos]) {
            case 0: $num_to_word = ""; break;
            case 1: $num_to_word = "one "; break;
            case 2: $num_to_word = "two "; break;
            case 3: $num_to_word = "three "; break;
            case 4: $num_to_word = "four "; break;
            case 5: $num_to_word = "five "; break;
            case 6: $num_to_word = "six "; break;
            case 7: $num_to_word = "seven "; break;
            case 8: $num_to_word = "eight "; break;
            case 9: $num_to_word = "nine "; break;
        }
    }
    if($array_no[$pos] != 0 && ($pos == 2 || $pos == 5 || $pos == 8)){
        $num_to_word .= "hundred ";
        if($array_no[$pos-1] != 0 || $array_no[$pos-2] != 0)
        $num_to_word .= "and ";
    }
    if(($array_no[$pos] != 0 && $pos == 6) || ($array_no[$pos] == 1 && $pos == 7))
        $num_to_word .= "million ";
    if(($array_no[$pos] != 0 && $pos == 3) || ($array_no[$pos] == 1 && $pos == 4))
        $num_to_word .= "thousand ";

    return $num_to_word;
}

?>
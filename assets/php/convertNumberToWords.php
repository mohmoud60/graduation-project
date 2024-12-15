<?php
function convertNumberToWords($num) {
    $ones = array("", "واحد", "اثنان", "ثلاثة", "أربعة", "خمسة", "ستة", "سبعة", "ثمانية", "تسعة", "عشرة", "أحد عشر", "اثنا عشر", "ثلاثة عشر", "أربعة عشر", "خمسة عشر", "ستة عشر", "سبعة عشر", "ثمانية عشر", "تسعة عشر");
    $tens = array("", "", "عشرون", "ثلاثون", "أربعون", "خمسون", "ستون", "سبعون", "ثمانون", "تسعون");
    $hundreds = array("", "مائة", "مائتان", "ثلاثمائة", "أربعمائة", "خمسمائة", "ستمائة", "سبعمائة", "ثمانمائة", "تسعمائة");

    if ($num < 20) {
        return $ones[$num];
    } elseif ($num < 100) {
        return ($num % 10 > 0 ? $ones[$num % 10] . " و " : '') . $tens[floor($num / 10)];
    } elseif ($num < 1000) {
        return $hundreds[floor($num / 100)] . ($num % 100 > 0 ? " و " . convertNumberToWords($num % 100) : '');
    } elseif ($num < 2000) {
        return "ألف" . ($num % 1000 > 0 ? " و " . convertNumberToWords($num % 1000) : '');
    } elseif ($num < 1000000) {
        $thousands = floor($num / 1000);
        $remainder = $num % 1000;
        return convertNumberToWords($thousands) . " ألف" . ($remainder > 0 ? " و " . convertNumberToWords($remainder) : '');
    } elseif ($num < 1000000000) {
        $millions = floor($num / 1000000);
        $remainder = $num % 1000000;
        return convertNumberToWords($millions) . " مليون" . ($remainder > 0 ? " و " . convertNumberToWords($remainder) : '');
    } else {
        return "العدد كبير جداً";
    }
}

?>

<?php
namespace URLSafeB64;

function encode($input) {
    return str_replace("=", "", strtr(base64_encode($input), "+/", "-_"));
}

function decode($input) {
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $input .= str_repeat("=", $padlen);
    }
    return base64_decode(strtr($input, "-_", "+/"));
}
?>
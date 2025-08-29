<?php

/**
 * Date: 2025年8月28日
 * 有问题联系 纸飞机(Telegram): https://t.me/king_orz

 * 温馨提示：接受各种代码定制
 */
function hexString2Base58check($hexString)
{
    $address = hex2bin($hexString);
    $base58add = base58check_en($address);
    return $base58add;
}

//encode address from byte[] to base58check string
function base58check_en($address)
{
    $hash0 = hash("sha256", $address);
    $hash1 = hash("sha256", hex2bin($hash0));
    $checksum = substr($hash1, 0, 8);
    $address = $address . hex2bin($checksum);
    $base58add = base58_encode($address);
    return $base58add;
}

function strToUtf8($str)
{

    $encode = mb_detect_encoding($str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));

    if ($encode == 'UTF-8') {

        return $str;
    } else {

        return mb_convert_encoding($str, 'UTF-8', $encode);
    }
}

function is_utf8($string)

{
    return preg_match('%^(?:

[\x09\x0A\x0D\x20-\x7E]            # ASCII

| [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte

|  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs

| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte

|  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates

|  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3

| [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15

|  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16

)*$%xs', $string);
}

function base58_encode($string)
{
    $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $base = strlen($alphabet);
    if (is_string($string) === false) {
        return false;
    }
    if (strlen($string) === 0) {
        return '';
    }
    $bytes = array_values(unpack('C*', $string));
    $decimal = $bytes[0];
    for ($i = 1, $l = count($bytes); $i < $l; $i++) {
        $decimal = bcmul($decimal, 256);
        $decimal = bcadd($decimal, $bytes[$i]);
    }
    $output = '';
    while ($decimal >= $base) {
        $div = bcdiv($decimal, $base, 0);
        $mod = bcmod($decimal, $base);
        $output .= $alphabet[$mod];
        $decimal = $div;
    }
    if ($decimal > 0) {
        $output .= $alphabet[$decimal];
    }
    $output = strrev($output);
    foreach ($bytes as $byte) {
        if ($byte === 0) {
            $output = $alphabet[0] . $output;
            continue;
        }
        break;
    }
    return (string) $output;
}
function base58_decode($base58)
{
    $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    $base = strlen($alphabet);
    if (is_string($base58) === false) {
        return false;
    }
    if (strlen($base58) === 0) {
        return '';
    }
    $indexes = array_flip(str_split($alphabet));
    $chars = str_split($base58);
    foreach ($chars as $char) {
        if (isset($indexes[$char]) === false) {
            return false;
        }
    }
    $decimal = $indexes[$chars[0]];
    for ($i = 1, $l = count($chars); $i < $l; $i++) {
        $decimal = bcmul($decimal, $base);
        $decimal = bcadd($decimal, $indexes[$chars[$i]]);
    }
    $output = '';
    while ($decimal > 0) {
        $byte = bcmod($decimal, 256);
        $output = pack('C', $byte) . $output;
        $decimal = bcdiv($decimal, 256, 0);
    }
    foreach ($chars as $char) {
        if ($indexes[$char] === 0) {
            $output = "\x00" . $output;
            continue;
        }
        break;
    }
    return $output;
}
/**

 * Byte数组转字符串

 * @param  array $bytes

 * @return string

 */

function bytesToStr($bytes)

{

    $str = '';

    foreach ($bytes as $ch) {

        $str .= chr($ch);
    }

    return $str;
}

/**

 * 字符串转Byte数组

 * @param  string $string

 * @return array

 */

function strToBytes($string)

{

    $bytes = array();

    for ($i = 0; $i < strlen($string); $i++) {

        $bytes[] = ord($string[$i]);
    }

    return $bytes;
}

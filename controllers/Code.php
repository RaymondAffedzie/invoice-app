<?php

Class Code
{
    private static $alphaNumeric = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private static $digits = '0123456789';
    private static $uniqueValue = null;

    // generate a unique characters mixed alphanumeric characters
    public static function uniqueId($length = 15)
    {
        // Choose a random letter to be the first character of the unique ID
        self::$uniqueValue .= chr(rand(65, 90));

        for ($i = 1; $i < $length; $i++) {
            $index = rand(0, strlen(self::$alphaNumeric) - 1);
            self::$uniqueValue .= self::$alphaNumeric[$index];
        }

        return self::$uniqueValue;
    }

    // generate uuid v4 string (a 36 long characters of mixed alphanumeric)
    public static function uuid_v4()
    {
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Set version to 4
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    // generate a unique strings for digits for id
    public static function codeDigits($length = 9)
    {
        for ($i = 1; $i < $length; $i++) {
            $index = rand(0, strlen(self::$digits) - 1);
            self::$uniqueValue .= self::$digits[$index];
        }

        return self::$uniqueValue;
    }

    // Generate a 6 digit length OTP
    public static function OTP()
    {
        $otpLength = 6;
        for ($i = 0; $i < $otpLength; $i++) {
            self::$uniqueValue .= self::$digits[rand(0, strlen(self::$digits) - 1)];
        }
        return self::$uniqueValue;
    }
}
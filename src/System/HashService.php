<?php
namespace Cosmic\System;

class HashService
{
    public static function password($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

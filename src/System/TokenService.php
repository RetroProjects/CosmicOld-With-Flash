<?php
namespace Cosmic\System;

use Cosmic\App\Config;
use Exception;

class TokenService
{
    protected $token;

    public function __construct($token_value = null)
    {
        if ($token_value) {
            $this->token = $token_value;
        } else {
            try {
                $this->token = bin2hex(random_bytes(16));
            } catch (Exception $e) {

            }
        }
    }

    public function getValue()
    {
        return $this->token;
    }

    public function getHash()
    {
        return hash_hmac('sha512', $this->token, Config::SECRET_TOKEN);
    }

    public static function authTicket($player_id)
    {
        return sha1(substr(md5(rand(-10000, 10000)), 0, 6).substr(md5(rand(-20, 10000)), 0, 10).$player_id).''.md5($player_id);
    }
}

<?php
namespace Cosmic\App\Models;

use Cosmic\App\Config;

use Cosmic\System\Locale;
use Cosmic\System\View;
use Cosmic\System\MailService;
use Cosmic\System\TokenService;

use Cosmic\System\DatabaseService as QueryBuilder;
use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use PDO;

class Password
{
    public static function getByToken($token, $hash = false)
    {
        if($hash) {
            $token = new TokenService($token);
            $token = $token->getHash();
        }

        return QueryBuilder::connection()->table('website_password_reset')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('token', $token)->first();
    }

    public static function deleteToken($email)
    {
        return QueryBuilder::connection()->table('website_password_reset')->where('email', $email)->delete();
    }

    public static function createToken($player_id, $username, $email)
    {
        $token = new TokenService();
        $hashed_token = $token->getHash();

        $data = array(
            'player_id'     => $player_id,
            'email'         => $email,
            'ip_address'    => getIpAddress(),
            'token'         => $hashed_token,
            'timestamp'     => time() + 7200
        );

        QueryBuilder::connection()->table('website_password_reset')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);

        return self::sendMail($username, $email, $token->getValue());
    }

    public static function sendMail($username, $email, $token) {
        $url	= 'https://' . Config::site['domain'].'/password/reset/' . $token;
        $body 	= ViewService::getTemplate('Password/body.html', ['url' => $url, 'username' => $username], true, true);
        return MailService::send(LocaleService::get('claim/email/title'), $body, $email);
    }
}

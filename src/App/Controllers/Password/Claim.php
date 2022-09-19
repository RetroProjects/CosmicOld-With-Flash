<?php
namespace Cosmic\App\Controllers\Password;

use Cosmic\App\Models\Player;
use Cosmic\App\Models\Password;
use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Claim
{
    public function validate()
    {
        ValidationService::validate([
            'username'              => 'required|max:30',
            'email'                 => 'required|max:72|email',
            'g-recaptcha-response'  => 'captcha'
        ]);

        $username   = input('username');
        $email      = input('email');

        $player = Player::getDataByUsername($username, array('id', 'username', 'mail'));
        if ($player == null || strtolower($player->mail) != strtolower($email)) {
            response()->json(["status" => "error", "message" => LocaleService::get('claim/invalid_email'), "replacepage" => "password/claim"]);
        }

        Password::createToken($player->id, $player->username, $player->mail);
        response()->json(["status" => "success", "message" => LocaleService::get('claim/send_link'), "replacepage" => "password/claim"]);
    }

    public function index()
    {
        ViewService::renderTemplate('Password/claim.html', [
            'title' => LocaleService::get('core/title/password/claim'),
            'page'  => 'password_claim'
        ]);
    }
}
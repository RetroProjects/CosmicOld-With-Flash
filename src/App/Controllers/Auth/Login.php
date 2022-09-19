<?php
namespace Cosmic\App\Controllers\Auth;

use Cosmic\App\Config;
use Cosmic\App\Controllers\Auth\Auth;
use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\SessionService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;
use Cosmic\System\HashService;

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Login
{
    private $auth;

    public function logout()
    {
        Auth::logout();
        redirect('/');
    }

    public function request()
    {
        ValidationService::validate([
            'username' => 'required|min:1|max:30',
            'password' => 'required|min:1|max:100',
            'pincode'  => 'max:6'
        ]);
      
        $pin_code     = !empty(input('pincode')) ? input('pincode') : false;

        $player = Player::getDataByUsername(input('username'), array('id', 'username', 'password', 'rank', 'secret_key', 'pincode'));
        if ($player == null || !HashService::verify(input('password'), $player->password)) {
            response()->json(["status" => "error", "message" => LocaleService::get('login/invalid_password')]);
        }

        /*
        *  Verification authentication
        */

        if(!$pin_code) {
            if (!empty($player->secret_key) || !empty($player->pincode)) {
                response()->json(["status" => "pincode_required"]);
            }
        }

        if ($pin_code && empty($player->secret_key) && empty($player->pincode)) {
            response()->json(["status" => "error", "message" => LocaleService::get('login/invalid_pincode')]);
        }
      
        if(!empty($player->pincode) && empty($player->secret_key)) {
            if($player->pincode !== $pin_code) {
                response()->json(["status" => "error", "message" => LocaleService::get('login/invalid_pincode')]);
            }
        }

        if(!empty($player->secret_key) && empty($player->pincode)) {
            $this->googleAuthentication($pin_code, $player->secret_key);
        }

        /*
        *  End authentication
        */

        $this->login($player);
    }

    protected function login(Player $user)
    {
        if ($user && Auth::login($user)) {
            response()->json(["status" => "error", "location" => "/home"]);
        } else {
            response()->json(["status" => "error", "message" => LocaleService::get('login/invalid_password')]);
        }
    }

    protected function googleAuthentication($pin_code, $secret_key)
    {
        $this->auth = new GoogleAuthenticator();

        if (!$this->auth->checkCode($secret_key, $pin_code)) {
            response()->json(["status" => "error", "message" => LocaleService::get('login/invalid_pincode')]);
        }

        return true;
    }
}

<?php
namespace Cosmic\App\Controllers\Password;

use Cosmic\App\Models\Log;
use Cosmic\App\Models\Password;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

use stdClass;

class Reset
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function validate()
    {
        ValidationService::validate([
            'new_password'      => 'required|min:6|max:32',
            'repeated_password' => 'required|min:6|max:32|same:new_password',
            'token'             => 'max:150'
        ]);

        $token = input('token');
        $newPassword = input('new_password');

        $player = Password::getByToken($token, true);
        if ($player == null || $player->timestamp < time()) {
            if($player->timestamp < time()) {
                Password::deleteToken($player->email);
            }

            response()->json(["status" => "error", "message" => LocaleService::get('claim/invalid_link'), "pagetime" => "/home"]);
        }

        Player::update($player->player_id, ['pincode' => null]);
        Player::resetPassword($player->player_id, $newPassword);
        Password::deleteToken($player->email);

        response()->json(["status" => "success", "message" => LocaleService::get('claim/password_changed'), "pagetime" => "/home"]);
    }

    public function index($token)
    {
        $player = Password::getByToken($token, true);
        if ($player == null) {
            redirect('/');
        }

        ViewService::renderTemplate('Password/reset.html', [
            'title' => LocaleService::get('core/title/password/reset'),
            'page'  => 'password_reset',
            'data'  => $this->data,
            'token' => $token
        ]);

        return false;
    }
}

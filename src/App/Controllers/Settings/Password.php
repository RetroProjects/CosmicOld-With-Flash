<?php
namespace Cosmic\App\Controllers\Settings;

use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;
use Cosmic\System\HashService;
use Cosmic\System\SessionService;

use stdClass;

class Password
{
    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function request()
    {
        ValidationService::validate([
            'current_password'  => 'required|min:6',
            'new_password'      => 'required|min:6|max:32',
            'repeated_password' => 'required|same:new_password'
        ]);

        $currentPassword = input('current_password');
        $this->data->newpin = input('new_password');

        if (!HashService::verify($currentPassword, request()->player->password)) {
            response()->json(["status" => "error", "message" => LocaleService::get('settings/current_password_invalid')]);
        }
      
        Player::resetPassword(request()->player->id, $this->data->newpin);
        SessionService::destroy();

        response()->json(["status" => "success", "message" => LocaleService::get('settings/password_saved'), "pagetime" => "/home"]);
    }

    public function index()
    {
        ViewService::renderTemplate('Settings/password.html', [
            'title' => LocaleService::get('core/title/settings/password'),
            'page'  => 'settings_password',
            'data'  => $this->data
        ]);
    }
}

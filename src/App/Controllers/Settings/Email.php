<?php
namespace Cosmic\App\Controllers\Settings;

use Cosmic\App\Models\Log;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;
use Cosmic\System\HashService;

use Library\Json;

class Email
{
    public function validate()
    {
        ValidationService::validate([
            'current_password' => 'required|max:100',
            'email'            => 'required|min:6|max:150|email'
        ]);

        $currentPassword = input('current_password');
        $email = input('email');

        if (!HashService::verify($currentPassword, request()->player->password)) {
            response()->json(["status" => "error", "message" => LocaleService::get('settings/current_password_invalid')]);
        }

        Player::update(request()->player->id, ['mail' => $email]);
        response()->json(["status" => "success", "message" => LocaleService::get('settings/email_saved'), "replacepage" => "settings/email"]);
    }

    public function index()
    {
        ViewService::renderTemplate('Settings/email.html', [
            'title' => LocaleService::get('core/title/settings/email'),
            'page'  => 'settings_email'
        ]);
    }
}

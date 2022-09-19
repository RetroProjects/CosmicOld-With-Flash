<?php
namespace Cosmic\App\Controllers\Settings;

use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;
use Cosmic\System\HashService;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Verification
{
    public function validate()
    {
        $this->auth = new GoogleAuthenticator();

        ValidationService::validate([
            'current_password'  => 'required|min:4'
        ]);

        if (!HashService::verify(input('current_password'), request()->player->password)) {
            response()->json(["status" => "error", "message" => LocaleService::get('login/invalid_password')]);
        }

        $verification_enabled = filter_var(input('enabled'), FILTER_VALIDATE_BOOLEAN);

        /*
        *  Google Authentication
        */

        if(input()->post('type')->value == "app") {


            if (!$this->auth->checkCode(input('data'), input()->post('input')->value)) {
                response()->json(["status" => "error", "message" => LocaleService::get('settings/invalid_secretcode')]);
            }

            if($verification_enabled && request()->player->pincode == null) {
                Player::update(request()->player->id, ['secret_key' => input('data')]);
                response()->json(["status" => "success", "message" => LocaleService::get('settings/enabled_secretcode'), "pagetime" =>"/logout"]);
            }

            Player::update(request()->player->id, ['secret_key' => NULL]);
            response()->json(["status" => "success", "message" => LocaleService::get('settings/disabled_secretcode'), "replacepage" => "settings/verification"]);
        }

        /*
        *  Pincode Authentication
        */
        if(input()->post('type')->value == "pincode") {

            if($verification_enabled && request()->player->secret_key == NULL) {
                Player::update(request()->player->id, ['pincode' => input('data')]);
                response()->json(["status" => "success", "message" => LocaleService::get('settings/enabled_secretcode'), "pagetime" => "/logout"]);
            }

            Player::update(request()->player->id, ['pincode' => null]);
            response()->json(["status" => "success", "message" => LocaleService::get('settings/disabled_secretcode'), "replacepage" => "settings/verification"]);
        }
    }

    public function index()
    {
        ViewService::renderTemplate('Settings/verification.html', [
            'title' => LocaleService::get('core/title/settings/index'),
            'page'  => 'settings_verification',
            'token' => (!request()->player->secret_key ? (new GoogleAuthenticator())->generateSecret() : request()->player->secret_key),
            'auth_enabled' => (request()->player->secret_key || (request()->player->pincode != NULL))
        ]);
    }
}

<?php
namespace Cosmic\App\Controllers\Settings;

use Cosmic\App\Config;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

use Cosmic\App\Library\HotelApi;

class Preferences
{
    public function validate()
    {
        $inArray = array(
            'block_following',
            'block_friendrequests',
            'block_roominvites',
            'old_chat',
            'block_alerts',
            'themeswitch'
        );

        $column = input('post');
        $type   = (int)filter_var(input('type'), FILTER_VALIDATE_BOOLEAN);

        if (!is_int($type) || !in_array($column, $inArray)) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong'), "captcha_error" => "error"]);
        }
      
        if($column == "themeswitch") {
            $skin = (request()->player->template == "light") ? "dark" : "light";
          
            Player::update(request()->player->id, ['template' =>  $skin]);
            setcookie("template", $skin, strtotime( '+30 days' ), "/"); 
          
            response()->json(["status" => "success", "location" => "/settings"]);
        }

        if (request()->player->online) {
            HotelApi::execute('updateuser', array('user_id' => request()->player->id, $column => $type));
        } else {
            Player::updateSettings(request()->player->id, $column, $type);
        }

        response()->json(["status" => "success", "message" => LocaleService::get('settings/preferences_saved')]);
    }

    public function index()
    {
        ViewService::renderTemplate('Settings/preferences.html', [
            'title' => LocaleService::get('core/title/settings/index'),
            'page'  => 'settings_preferences',
            'data'  => Player::getSettings(request()->player->id)
        ]);
    }
}
<?php
namespace Cosmic\App\Controllers\Auth;

use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Log;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\SessionService;

use Cosmic\App\Helpers\Helper;

class Auth
{
    public static function login(Player $player)
    {
        if (Helper::asnBan()) {
            response()->json(["status" => "error", "message" => LocaleService::get('asn/login')]); 
        }

        if (in_array('housekeeping', array_column(Permission::get($player->rank), 'permission'))) {
            Log::addStaffLog('-1', 'Staff logged in: ' . getIpAddress(), $player->id, 'LOGIN');
        }

        SessionService::set(['player_id' => $player->id, 'ip_address' => getIpAddress(), 'agent' => $_SERVER['HTTP_USER_AGENT']]);
        Player::update($player->id, ['ip_current' => getIpAddress(), 'last_online' => time()]);

        return $player;
    }

    public static function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public static function maintenance()
    {
        return Core::settings()->maintenance ?? false;
    }
}

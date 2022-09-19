<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Core;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Dashboard
{
    public function latestplayers()
    {
        $latest_users = Admin::getLatestPlayers();
        if ($latest_users == null) {
            exit;
        }

        foreach ($latest_users as $row) {
            $row->id = $row->id;
            $row->last_login  = $row->online ? 'Online' : date("d-m-Y H:i:s", $row->last_login);
            $row->ip_current  = Helper::convertIp($row->ip_current);
            $row->ip_register = Helper::convertIp($row->ip_register);

            if (!Permission::exists('housekeeping_change_email', request()->player->rank)) {
                $row->mail = '';
            }
        }

        Json::filter($latest_users, 'desc', 'id');
    }

    public function latestnamechanges()
    {
        Json::filter(Admin::getNameChanges(), 'desc', 'id');
    }

    public function usersonline()
    {
        $online_users = Admin::getOnlinePlayers();

        foreach ($online_users as $row) {
            $row->ip = Helper::convertIp($row->ip_register);
        }

        Json::filter($online_users, 'desc', 'id');
    }
  
    public function maintenance()
    {
        if (!Permission::exists('housekeeping_permissions', request()->player->rank)) {
            response()->json(["status" => "error", "message" => "You have no permissions to do this!"]);
        }
      
        $maintenance = Admin::saveSettings('maintenance', (Core::settings()->maintenance == "1") ? "0" : "1");
        response()->json(["status" => "success", "message" => "Maintenance updated"]);
    }
  
    public function clearcache()
    {
        Admin::saveSettings('cache_timestamp', md5(time()));
        response()->json(["status" => "success", "message" => "Cache deleted!"]);
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/home.html', ['permission' => 'housekeeping']);
    }
}

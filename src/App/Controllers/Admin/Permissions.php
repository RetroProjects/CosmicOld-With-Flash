<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Permission;

use Cosmic\App\Library\Json;
use Cosmic\System\ViewService;


use stdClass;
use QueryBuilder;

class Permissions
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function getranks()
    {
        echo Json::encode(Admin::getRanks(true));
    }

    public function getpermissioncommands()
    {
        //* todo https://asteroidcms.nl/housekeeping/permissions/manage#
    }

    public function changepermissionrank()
    {
        $command_id = input()->post('command_id')->value;
        $minimum_rk = filter_var(input()->post('minimum_rank')->value, FILTER_SANITIZE_NUMBER_INT);

        if (Admin::changeMinimumRank($command_id, $minimum_rk)) {
            response()->json(["status" => "success", "message" => "Permission rank has been changed!"]);
        }
    }

    public function createrank()
    {     
        $commandsArray = json_decode(input()->post('value')->value);
        $permissionsArray = json_decode(input()->post('post')->value, true);

        foreach ($commandsArray as $key => $item) {
            if ($item->id == "fname") {
                $this->data->name = $item->value;
            } else {
                $obj = $item->id;
                if ($item->value === "on") {
                    $this->data->$obj = '0';
                } else {
                    $this->data->$obj = $item->value;
                }
            }
        }

        if (empty($this->data->rank_name)) {
            response()->json(["status" => "error", "message" => "Rank can not be empty!"]);
        }
      
        if (in_array($this->data->rank_name, array_column(Admin::getRanks(true), 'name'))) {
            response()->json(["status" => "error", "message" => "Rank name is already in use!"]);
        }
  
        Admin::addRank($this->data, $permissionsArray);
        response()->json(["status" => "success", "message" => "Rank added successfully!"]);
    }

    public function getwebsiteranks()
    {
        $this->data->ranks = Admin::getAllWebPermissions();
        Json::filter($this->data->ranks, 'desc', 'id');
    }

    public function edit()
    {
        $this->data->ranks = Admin::getRankById(input()->post('post')->value);
        echo Json::encode($this->data);
    }

    public function wizard()
    {
        $permission = Admin::getWebPermissions(input()->post('post')->value);
        echo Json::encode($permission);
    }

    public function deleteteam()
    {
        Admin::deleteTeam(input()->post('id')->value);
        Admin::updateTeamPlayer(input()->post('id')->value);
        response()->json(["status" => "success", "message" => "Team has been deleted!"]);
    }
  
    public function addteam()
    {
        Admin::addTeam(input()->post('rank_name')->value, input()->post('rank_desciption')->value);
        response()->json(["status" => "success", "message" => "Team is added!"]);
    }
  
    public function getteams()
    {
        Json::filter(Permission::getTeams(), 'desc', 'id');
    } 
 
    public function getpermissions()
    {
        $this->data->permissions = Permission::get(input()->post('roleid')->value);
        Json::filter($this->data->permissions, 'desc', 'id');
    }

    public function search()
    {
        response()->json(["status" => "success", "message" => "Permissions has been loaded!"]);
    }

    public function addpermission()
    {
        $role_id = input()->post('roleid')->value;
        $permission_id = input()->post('permissionid')->value;

        if (empty($role_id) || empty($permission_id)) {
            response()->json(["status" => "error", "message" => "Permission can not be added!"]);
        }

        if (Admin::roleExists($role_id, $permission_id))  {
            response()->json(["status" => "error", "message" => "Permissions has already added to this role!"]);
        }

        Admin::createPermission($role_id, $permission_id);
        response()->json(["status" => "success", "message" => "Permissions has been added!"]);
    }

    public function delete()
    {
        $permission = Permission::getPermissionById(input()->post('id')->value);
        if (empty($permission)) {
            response()->json(["status" => "error", "message" => "No permission found!"]);
        }

        Admin::deletePermission($permission->id);
        response()->json(["status" => "success", "message" => "Permissions has been deleted!"]);
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/Management/permissions.html', ['permission' => 'housekeeping_permissions', 'permission_columns' => Permission::getAllColumns()]);
    }
}
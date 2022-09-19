<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Config;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Log;
use Cosmic\App\Models\Player;
use Cosmic\App\Library\Upload;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;


use stdClass;

class RareValue
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function addpage()
    {
        ValidationService::validate([
            'name'         => 'required|max:50',
            'desc'   => 'required|max:200',
            'thumb'    => 'required'
        ]);


        $id = input()->post('pageid')->value ?? 0;
        $newid = input()->post('newid')->value ?? null;

        $name = input()->post('name')->value;
        $desc = input()->post('desc')->value;
        $thumb = input()->post('thumb')->value;

        if ($id == 0) {
            Admin::addRareValuePage($newid, $name, $desc, $thumb);
            Log::addStaffLog('-1', 'New Rare Value Page placed: ' . $name, request()->player->id, 'rarevalue');
            response()->json(["status" => "success", "message" => "Page created successfully!"]);
        }

        Admin::editRarevaluePage($id, $name, $desc, $thumb, $newid);
        Log::addStaffLog('-1', 'RareValuePage edit: ' . $name, request()->player->id, 'rarevalue');
        response()->json(["status" => "success", "message" => "Page edited successfully"]);
    }

    public function removepage()
    {
        ValidationService::validate([
            'post' => 'required|min:1'
        ]);

        $page = input()->post('post')->value;

        $page_bc = Admin::getRareValuePageById($page);
        if (empty($page_bc)) {
            response()->json(["status" => "error", "message" => "PAG: ID({$page}) is already removed"]);
        }

        Admin::deleteRareValuePageById($page);

        Log::addStaffLog('-1', 'Removed a RareValuePage: ' . $page, request()->player->id, 'rarevalue');
        response()->json(["status" => "success", "message" => "PAG: ID({$page}) successfully removed"]);
    }

    public function additem()
    {
        ValidationService::validate([
            'name'   => 'required|max:200|min:1',
            'parent_id' => 'numeric',
            'item_id'   => 'required',
            'cost_credits'    => 'required',
            'cost_points'    => 'required',
            'points_type'    => 'required',
            'image'    => 'required'
        ]);

        $id = input()->post('id')->value ?? 0;
        $page_id = input()->post('parent_id')->value ?? 0;
        $name = input()->post('name')->value;
        $item_id = input()->post('item_id')->value;
        $cost_credits = input()->post('cost_credits')->value;
        $cost_points = input()->post('cost_points')->value;
        $points_type = input()->post('points_type')->value;
        $image = input()->post('image')->value;

        if ($id == 0) {
            Admin::addRareValueItem($name, $page_id, $item_id, $cost_credits, $cost_points, $points_type, $image, request()->player->id);
            Log::addStaffLog('-1', 'New Rare Value Item placed: ' . $name, request()->player->id, 'rarevalue');
            response()->json(["status" => "success", "message" => "New Item is added!"]);
        }

        Admin::editRarevalueItem($id, $name, $page_id, $image, request()->player->id, $item_id, $cost_credits, $cost_points, $points_type);
        Log::addStaffLog('-1', 'RareValueItem edit: ' . $name, request()->player->id, 'rarevalue');

        response()->json(["status" => "success", "message" => "Item edited successfully"]);
    }

    public function removeitem()
    {
        ValidationService::validate([
            'post' => 'required|min:1'
        ]);

        $item = input()->post('post')->value;
        $item_bc = Admin::getRareValueItemById($item);
      
        if (empty($item_bc)) {
            response()->json(["status" => "error", "message" => "ITEM: ID({$item}) is already removed"]);
        }

        Admin::deleteRareValueItemById($item);
        Log::addStaffLog('-1', 'Removed a RareValueItem ' . $item, request()->player->id, 'rarevalue');
        response()->json(["status" => "success", "message" => "ITEM: ID({$item}) successfully removed"]);
    }

    public function edititem()
    {
        if (empty(input()->post('post')->value)) {
            response()->json(["status" => "error", "message" => "We were unable to find this item"]);
        }

        $this->data->rarevalueitem = Admin::getRareValueItemById(input()->post('post')->value);
        echo Json::encode($this->data);
    }
  
    public function editpage()
    {
        if (empty(input()->post('post')->value)) {
            response()->json(["status" => "error", "message" => "We were unable to find this page"]);
        }

        $this->data->rarevalue = Admin::getRareValuePageById(input()->post('post')->value);
        echo Json::encode($this->data);
    }
  
    public function getpages()
    {
        $pages = Admin::getRareValuePages();
        Json::filter($pages, 'desc', 'id');
    }
  
    public function getitems()
    {
        $items = Admin::getRareValueItems();
        Json::filter($items, 'desc', 'id');
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/Management/rarevalue.html', ['permission' => 'housekeeping_rarevalue_control']);
    }
}

<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Config;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Core;
use Cosmic\App\Library\HotelApi;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Catalog
{
    private $data;

    public function __construct()
    {
        $this->data = new \stdClass();
    }
  
    public function request()
    {
        ValidationService::validate([
            'catid'       => 'required|numeric',
            'caption'     => 'required|max:50',
            'parent_id'   => 'required',
            'page_layout' => 'required',
            'visible'     => 'numeric|pattern:^(?:1OR0)$',
            'enabled'     => 'numeric|pattern:^(?:1OR0)$',
        ]);

        $catid = input()->post('catid')->value;
        $caption = input()->post('caption')->value;
        $page_headline = input()->post('page_headline')->value;
        $page_teaser = input()->post('page_teaser')->value;
        $parent_id = (input()->post('parent_id')->value == '1') ? '-1' : input()->post('parent_id')->value;
        $page_layout = input()->post('page_layout')->value;
        $visible = input()->post('visible')->value;
        $enabled = input()->post('enabled')->value;
        $create = input()->post('create')->value;

        $catalogue = Admin::getCatalogPagesById(input()->post('catid')->value);
        $query = Admin::updateCatalogPages($catid, $caption, $page_teaser, $page_headline, $parent_id, $page_layout, $visible, $enabled, $create);
      
        echo '{"status":"success","message":"Item is successfully editted!"}';
        exit;
    }
  
    public function additem()
    {
        ValidationService::validate([
            'sprite_id'               => 'numeric',
            'item_name'               => 'required',
            'public_name'             => 'required',
            'width'                   => 'required',
            'length'                  => 'required',
            'stack_height'            => 'required',
            'page_id'                 => 'required|numeric',
            'allow_stack'             => 'required|pattern:^(?:1OR0)$',
            'allow_sit'               => 'required|pattern:^(?:1OR0)$',
            'allow_lay'               => 'required|pattern:^(?:1OR0)$',
            'allow_walk'              => 'required|pattern:^(?:1OR0)$',
            'allow_gift'              => 'required|pattern:^(?:1OR0)$', 
            'allow_trade'             => 'required|pattern:^(?:1OR0)$',
            'allow_recycle'           => 'required|pattern:^(?:1OR0)$',
            'allow_marketplace_sell'  => 'required|pattern:^(?:1OR0)$',
            'allow_inventory_stack'   => 'required|pattern:^(?:1OR0)$',
            'type'                    => 'required',
            'interaction_type'        => 'required',
            'interaction_modes_count' => 'required',
            'page_id'                 => 'required|numeric',
            'cost_credits'            => 'required|numeric',
            'cost_points'             => 'required|numeric',
            'points_type'             => 'required|numeric',
            'amount'                  => 'required|numeric',
            'limited_sells'           => 'required|numeric',
            'limited_stack'           => 'required|numeric',
        ]);
          
        $furni_id = input()->post('furniture_id')->value ?? null;
 
        if($query = Admin::updateFurniture(array(
            'items_base' => array(
                'sprite_id'               => input()->post('sprite_id')->value,
                'item_name'               => input()->post('item_name')->value,
                'public_name'             => input()->post('public_name')->value,
                'width'                   => input()->post('width')->value,
                'length'                  => input()->post('length')->value,
                'stack_height'            => input()->post('stack_height')->value,
                'allow_stack'             => input()->post('allow_stack')->value,
                'allow_sit'               => input()->post('allow_sit')->value,
                'allow_lay'               => input()->post('allow_lay')->value,
                'allow_walk'              => input()->post('allow_walk')->value,
                'allow_gift'              => input()->post('allow_gift')->value,
                'allow_trade'             => input()->post('allow_trade')->value,
                'allow_recycle'           => input()->post('allow_recycle')->value,
                'allow_marketplace_sell'  => input()->post('allow_marketplace_sell')->value,
                'allow_inventory_stack'   => input()->post('allow_inventory_stack')->value,
                'type'                    => input()->post('type')->value,
                'interaction_type'        => input()->post('interaction_type')->value,
                'interaction_modes_count' => input()->post('interaction_modes_count')->value,
            ),
            'catalog_items' => array(
                'catalog_name'            => input()->post('catalog_name')->value,
                'cost_credits'            => input()->post('cost_credits')->value,
                'cost_points'             => input()->post('cost_points')->value,
                'points_type'             => input()->post('points_type')->value,
                'amount'                  => input()->post('amount')->value,
                'limited_sells'           => input()->post('limited_sells')->value,
                'limited_stack'           => input()->post('limited_stack')->value,
                'page_id'                 => input()->post('page_id')->value
            )
        ), $furni_id));


        echo '{"status":"success","message":"Item is successfully editted!"}';
        exit;
    }
  
    public function executeRcon() 
    {
        if(HotelApi::execute('updatecatalog')) {
            echo '{"status":"success","message":"Catalog !"}';
        }
    }
  
    public function move()
    {

        $validate = request()->validator->validate([
            'old_position'  => 'required|numeric',
            'new_position'  => 'required|numeric',
            'old_parent'    => 'required|numeric',
            'parent'        => 'required|numeric',
            'id'            => 'required|numeric',
            'positions'     => 'required'
        ]);

        if(!$validate->isSuccess()) {
            echo '{"status":"error","message":"Fill in all fields!"}';
            exit;
        }
          
        $old_position = input()->post('old_position')->value;
        $new_position = input()->post('new_position')->value;
        $old_parent = input()->post('old_parent')->value;
        $new_parent = input()->post('parent')->value;
        $children = input()->post('children')->value;
        $positions = input()->post('positions')->value;
        $id = input()->post('id')->value;

        if($old_parent !== $new_parent) {
            Admin::updateParent($id, $new_parent);
        }
      
        $query = Admin::UpdateOrderNumFromParent($positions);
        $array = explode(',', $positions);

        $i = 0;
        foreach($array as $pos) {
            $query = Admin::UpdateNewOrderNumFromParent($pos, $i);
            $i++;
        }
      
        echo '{"status":"success","message":"Folder is succesfully moved"}';
    }
  
    public function deleteparent()
    {
        ValidationService::validate([
            'id'  => 'required|numeric'
        ]);
      
        $id = input()->post('id')->value;
      
        if(Admin::deleteParent($id)) {
            $parents = Admin::getParents($id);
            foreach($parents as $parent) {
                Admin::deleteParent($parent->id);
            }
        }
      
        echo '{"status":"success","message":"Folder is succesfully deleted"}';
    }
  
    public function tree() 
    {
        $frontpage = Admin::getPageFromParentId();
      
        foreach($frontpage as $page) 
        {
            $parentid = $page->parent_id;
            if($parentid == '-1' || $parentid == '0') $parentid = "#";
          
            $data[] = [
               "id" => $page->id,
               "parent" => ($page->parent_id == '-1') ? $parentid : $page->parent_id,
               "text" => $page->caption,
               "icon" => "fa fa-folder icon-lg m--font-default"
            ];
        }
      
        response()->json(["data" => $data]);
    }
  
    public function getFurnitureById()
    {
        $this->data->itemsids = Admin::getCatalogItemsByItemIds(input()->post('post')->value);
        $this->data->furniture = Admin::getFurnitureById(input()->post('post')->value);

        response()->json(["data" => $this->data]);
    }
  
    public function getCatalogItemsByItemId()
    {
        $itemsids = Admin::getCatalogItemsByPageId(input()->post('id')->value);
        foreach($itemsids as $item) {
            $getCurrency = array_search($item->points_type, array_column(Core::getCurrencys(), 'type'));
            $item->club_only = ($item->club_only == 0) ? 'No' : 'Yes';
            $item->cost_points = ($item->points_type != 0) ? $item->cost_points . ' (' . ucfirst(Core::getCurrencys()[$getCurrency]->currency) . ')' : '';
        }
      
        echo Json::filter($itemsids, 'desc', 'id');
    }
  
    public function getCatalogByPageId()
    {
        $this->data->page = Admin::getCatalogPagesById(input()->post('post')->value);
        if(isset($this->data->page->parent_id)) {
            $this->data->page->parent = Admin::getCatalogPagesById($this->data->page->parent_id) ?? 0;
        }
      
        $this->data->items = Admin::getCatalogItemsByPageId(input()->post('post')->value);
        response()->json(["data" => $this->data]);
    }
  
    public function getCatalogPages()
    {
        $pages = Admin::getCatalogPages();
        if ($pages == null) {
            exit;
        }
      
        foreach ($pages as $row) {
            $row->page_texts = !empty($row->page_texts) ? $row->page_texts : 'Empty';
            $row->enabled = $row->enabled ? 'Yes' : 'No';
            $row->visible = $row->visible ? 'Yes' : 'No';
        }
      
        response()->json($pages);
    }
  
    public function view()
    {
        ViewService::renderTemplate('Admin/Management/catalog.html', ['permission' => 'housekeeping_server_catalog']);
    }
}

<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Permission;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;


class Settings
{
    public function __construct() 
    {
        $this->settings = Core::settings();
        $this->data = new \stdClass();
    }
  
    public function save()
    {
        foreach(input()->all() as $column => $value) {

            if($column == "vip_gift_items") {
              
                foreach(input()->post('vip_gift_items') as $gifts) {
                    unset($gifts->index);
                    unset($gifts->name);
                }
                
                $value = json_encode(input()->post('vip_gift_items'));
            }
          
            Admin::saveSettings($column, $value);
        }
      

        response()->json(["status" => "success", "message" => "Saved!"]);
    }
  
    public function addCurrency()
    {
        $currency = input()->post('currency')->value;
        $type = input()->post('type')->value;
        $amount = input()->post('amount')->value;
      
        $users = Player::getAllUsers(["id"]);
        foreach($users as $row) {
            Player::createCurrency($row->id, $type);
        }
      
        Core::addCurrency($currency, $type, $amount);
        response()->json(["status" => "success", "message" => "Currency has been added!"]);
    }
  
    public function deleteCurrency()
    {
        $type = input()->post('type')->value;
      
        $users = Player::getAllUsers();
        foreach($users as $row) {
            Player::deleteCurrency($row->id, $type);
        }
      
        if(Core::deleteCurrency($type, input()->post('currency')->value)) {
            response()->json(["status" => "success", "message" => "Currency has been deleted"]);
        }
    }
  
    public function getItems()
    {
        $this->settings->vip_gift_items = json_decode($this->settings->vip_gift_items);
        if(!empty($this->settings->vip_gift_items)) {
            foreach($this->settings->vip_gift_items->value as $item) {
                $item->name = Admin::getFurnitureById($item->value)->item_name;
            }
        }
      
        echo json_encode($this->settings->vip_gift_items->value);
    }
  
    public function getCurrencys()
    {
        response()->json(Core::getCurrencys());
    }
  
    public function view()
    {
        $this->settings->vip_badges = json_decode($this->settings->vip_badges,true);
        $this->settings->vip_currency_type = Core::getCurrencyByType($this->settings->vip_currency_type);
        $this->settings->namechange_currency_type = Core::getCurrencyByType($this->settings->namechange_currency_type);
        $this->settings->draw_badge_currency = Core::getCurrencyByType($this->settings->draw_badge_currency);
        $this->settings->user_of_the_week = Player::getDataById($this->settings->user_of_the_week ?? 0, ['id', 'username']) ?? false;
        $this->settings->ranks = Permission::getRanks();

        ViewService::renderTemplate('Admin/Management/settings.html', ['settings' => $this->settings, 'permission' => 'housekeeping_config']);
    }
}

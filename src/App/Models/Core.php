<?php
namespace Cosmic\App\Models;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

class Core
{
    public static function settings()
    {
        $settings = QueryBuilder::connection()->table('website_settings')->get();
      
        $inArray = new \stdClass();
      
        foreach($settings as $setting) {
            $key           = $setting->key;
            $inArray->$key = $setting->value;
        }
      
        return $inArray;
    }
  
    public static function getCurrencys()
    {
        return QueryBuilder::connection()->table('website_settings_currencys')->get();
    }
  
    public static function addCurrency($currency, $type, $amount)
    {
        return QueryBuilder::connection()->table('website_settings_currencys')->insert(array('currency'=> $currency, 'type' => $type, 'amount' => $amount));
    }
  
    public static function deleteCurrency($type, $currency)
    {
        return QueryBuilder::connection()->table('website_settings_currencys')->where('type', $type)->where('currency', $currency)->delete();
    }
  
    public static function getCurrencyByType($type)
    {
        return QueryBuilder::connection()->table('website_settings_currencys')->where('type', $type)->first();
    }
  
    public static function getRegisteredUsers()
    {
        return QueryBuilder::connection()->table('users')->count();
    }
  
    public static function getCatalogPages()
    {
        return QueryBuilder::connection()->table('catalog_pages')->count();
    }
  
    public static function getCatalogItems()
    {
        return QueryBuilder::connection()->table('catalog_items')->count();
    }
 
    public static function getItems()
    {
        return QueryBuilder::connection()->table('items_base')->count();
    }

    public static function getOnlineCount()
    {
        return QueryBuilder::connection()->table('users')->where('online', "1")->count();
    }
}
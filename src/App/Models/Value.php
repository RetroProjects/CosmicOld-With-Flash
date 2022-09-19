<?php
namespace Cosmic\App\Models;

use Cosmic\App\Config;
use Cosmic\App\Models\Admin;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

class Value
{
    public static $allItems = array();
    public static $allSubItems = array();
  
    public static function myItems($user_id)
    {
        return QueryBuilder::connection()->table('items')->select(QueryBuilder::connection()->raw('COUNT(*) as count'))->select('items.item_id')->select('catalog_items.catalog_name')
                  ->join('items_base', 'items.item_id', '=', 'items_base.id')->join('catalog_items', 'items.item_id', '=', 'catalog_items.id')
                  ->where('items_base.allow_marketplace_sell', '1')->where('items.user_id', $user_id)->groupBy('items.item_id')->get();
    }
  
    public static function mySales($user_id)
    {
        return QueryBuilder::connection()->table('website_marketplace')->select('website_marketplace.*')->select('catalog_items.catalog_name')
                  ->join('catalog_items', 'catalog_items.id', '=', 'website_marketplace.item_id')->where('website_marketplace.user_id', $user_id)->get();
    }
  
    public static function deleteOffer($item_id)
    {
        return QueryBuilder::connection()->table('website_marketplace')->where('id', $item_id)->delete();
    }
  
    public static function getFirstItem($item_id, $user_id)
    {
        return QueryBuilder::connection()->table('items')->where('item_id', $item_id)->where('user_id', $user_id)->first();
    }
  
    public static function deleteItem($id)
    {
        return QueryBuilder::connection()->table('items')->where('id', $id)->delete();
    }
  
    public static function allSellItems()
    {
        return QueryBuilder::connection()->table('website_marketplace')->select('website_marketplace.*')->select('catalog_items.*')->select('website_marketplace.id')
                  ->join('catalog_items', 'catalog_items.id', '=', 'website_marketplace.item_id')->get();
    }
  
    public static function ifCurrencyExists($currency) 
    {
        return QueryBuilder::connection()->table('website_settings_currencys')->find($currency, 'type');
    }
  
    public static function searchFurni($name)
    {
        return QueryBuilder::connection()->table('website_marketplace')->select('website_marketplace.*')->select('catalog_items.catalog_name')
                ->join('catalog_items', 'catalog_items.id', '=', 'website_marketplace.item_id')
                ->where('catalog_items.catalog_name', 'LIKE ', '%' . $name . '%')->get();
    }
  
    public static function sellItem($item_id, $user_id, $currency, $costs)
    {
        $data = array(
            'item_id'           => $item_id,
            'user_id'           => $user_id,
            'currency_type'     => $currency,
            'item_costs'        => $costs,
            'timestamp_added'   => time(),
            'timestamp_expire'  => strtotime('+7 days', time())
        );
      
        return QueryBuilder::connection()->table('website_marketplace')->insert($data);
    }
  
    public static function ifItemExists($item_id) 
    {
        return QueryBuilder::connection()->table('items_base')->where('id', $item_id)->count();
    }
  
    public static function ifMyItemExists($item_id, $user_id) 
    {
        return QueryBuilder::connection()->table('website_marketplace')->where('item_id', $item_id)->where('user_id', $user_id)->count();
    }
  
    public static function getOfferById($id) 
    {
        return QueryBuilder::connection()->table('website_marketplace')->where('id', $id)->first();
    }
  
    public static function getItem($id)
    {
        return QueryBuilder::connection()->table('catalog_items')->where('id', $id)->first();
    }
  
    public static function getValueCategorys()
    {
        return QueryBuilder::connection()->table('website_rare_values')->get();
    }
  
    public static function getFirstRare()
    {
        return QueryBuilder::connection()->table('website_rare_values')->first();
    }
  
    public static function getValueCategoryById($id)
    {
        return QueryBuilder::connection()->table('website_rare_values')->where('id', $id)->first();
    }
 
    public static function ifSubpageExists($id)
    {
        return QueryBuilder::connection()->query("SELECT id from catalog_pages WHERE parent_id = " . $id)->first();
    }
  
    public static function getCatalogItemsByParentId($id)
    {
        return QueryBuilder::connection()->query("SELECT id from catalog_pages WHERE parent_id IN ($id)")->get();
    }
  
    public static function getAllCatalogItems($page_id)
    {
        return QueryBuilder::connection()->query("SELECT DISTINCT catalog_items.*, (SELECT COUNT(*) FROM items WHERE item_id = catalog_items.id) AS amount FROM catalog_items WHERE page_id IN ($page_id)")->get();
    }
  
    public static function getValues($values, $transform = false)
    {

            /** Check if page has a subpage */
         
            foreach(json_decode($values->cat_ids) as $page_id) {
                (!empty(self::ifSubpageExists($page_id))) ? $pages[] = $page_id : $page[] = $page_id;
            }
         
           /**  Get all furni id's from page */
         
            if(!empty($page)) {
                self::$allItems = (self::getAllCatalogItems(join(',', array_map('intval', $page))));
            }
         
            /**  If page has a subpage get item id's */
         
            if(!empty($pages)) {
                $catalogSubpage = self::getCatalogItemsByParentId(join(',', array_map('intval', $pages)));
            }
          
            /** Get all furni id's from subpage */
         
            if(!empty($catalogSubpage)) {
                foreach($catalogSubpage as $items) {
                    $item[] = $items->id;
                }
                self::$allSubItems = self::getAllCatalogItems(join(',', array_map('intval', $item)));
            }
         
            /** Merge if subpage exists otherwise return page */
          
            $itemList = (!empty(self::$allSubItems)) ? array_merge_recursive(self::$allItems, self::$allSubItems) : self::$allItems;
 
            /** Get related item data */
            
            foreach($itemList as $item) {

                $currency = array_column(Core::getCurrencys(), 'currency', 'type');

                if($item->cost_points != 0 && !$transform) {
                    $item->cost_points = ($item->points_type != 0) ? $item->cost_points . ' (' . ucfirst($currency[$item->points_type]) . ')' : null;
                }
            }
        return $itemList ?? null;
    }
}
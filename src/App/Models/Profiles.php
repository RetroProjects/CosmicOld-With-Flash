<?php
namespace Cosmic\App\Models;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

class Profiles
{
    public static function hasWidget($user_id, $name)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $user_id)->where('name', $name)->get();
    }
  
    public static function hasBackground($user_id)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $user_id)->where('type', 'b')->get();
    }

    public static function getWidgets($user_id)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $user_id)->whereIn('type', array('s','w'))->orderBy('z')->get();
    }

    public static function getItems($data)
    {
        return QueryBuilder::connection()->table('website_profile_catalogues')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('type', $data)->get();
    }

    public static function getBackground($user_id)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('type', 'b')->where('user_id', $user_id)->first();
    }

    public static function getNotes($user_id)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $user_id)->where('type', 'n')->get();
    }

    public static function getCategorys()
    {
        return QueryBuilder::connection()->table('website_profile_catalogues_cats')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('type', 's')->get();
    }

    public static function saveBackground($user_id, $name)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $user_id)->where('type', 'b')->update(array('name' => $name));;
    }
  
    public static function insertBackground($user_id, $name)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert(array('user_id' => $user_id, 'name' => $name, 'type' => 'b'));   
    }
  
    public static function update($user_id, $name, $top, $left, $skin, $type)
    {
        $data = array(
            'name' => $name,
            'skin' => $skin,
            'x' => $left,
            'y' => $top,
            'type' => $type
        );
      
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('user_id', $user_id)->where('name', $name)->update($data);
    }

    public static function insert($user_id, $name, $top, $left, $skin, $type)
    {
        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'skin' => $skin,
            'x' => $left,
            'y' => $top,
            'type' => $type
        );
      
        return QueryBuilder::connection()->table('website_profile_homes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function remove($user_id, $item_id, $type)
    {
        return QueryBuilder::connection()->table('website_profile_homes')->where('id', $item_id)->where('user_id', $user_id)->where('type', $type)->delete();
    }
}


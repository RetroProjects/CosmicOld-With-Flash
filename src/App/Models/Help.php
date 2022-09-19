<?php
namespace Cosmic\App\Models;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

class Help
{
    public static function getCategories()
    {return  QueryBuilder::connection()->table('website_helptool_categories')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('id', 'asc')->get();
    }

    public static function getByCategory($category)
    {
        return  QueryBuilder::connection()->table('website_helptool_faq')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('category', $category)->orderBy('id', 'asc')->get();
    }
  
    public static function getById($slug)
    {
        return  QueryBuilder::connection()->table('website_helptool_faq')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($slug);
    }

    public static function createTicket($data, $player_id, $ip_address) {
        $data = array(
            'subject' => $data->subject,
            'message' => $data->message,
            'player_id' => $player_id,
            'ip_address' => $ip_address,
            'timestamp' => time()
        );

        return QueryBuilder::connection()->table('website_helptool_requests')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }

    public static function updateTicketStatus(String $action, $id)
    {
        $data = array(
            'status' => $action
        );

        return QueryBuilder::connection()->table('website_helptool_requests')->where('id', $id)->update($data);
    }

    public static function addTicketReaction($ticketid, $userid, $message) {
        $data = array(
            'request_id' => $ticketid,
            'practitioner_id' => $userid,
            'message' => $message,
            'timestamp' => time()
        );

        return QueryBuilder::connection()->table('website_helptool_reactions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    }
  
    public static function getTicketsByUserId($userid)
    {
        return  QueryBuilder::connection()->table('website_helptool_requests')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('player_id', $userid)->get();
    }

    public static function getRequestById($id, $userid)
    {
        return  QueryBuilder::connection()->table('website_helptool_requests')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('player_id', $userid)->find($id);
    }
  
    public static function countTicketsByUserId($id)
    {
        return  QueryBuilder::connection()->table('website_helptool_requests')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('player_id', $id)->count();
    }
  
    public static function getTicketReactions($id)
    {
        return  QueryBuilder::connection()->table('website_helptool_reactions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('request_id', $id)->get();
    }

    public static function latestHelpTicketReaction($ticketid)
    {
        return QueryBuilder::connection()->table('website_helptool_reactions')->where('request_id', $ticketid)->orderBy('id', 'desc')->first();
    }

}
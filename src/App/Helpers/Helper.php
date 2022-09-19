<?php
namespace Cosmic\App\Helpers;

use Cosmic\App\Config;

use Cosmic\App\Models\Ban;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Guild;

use Jenssegers\Date\Date;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;

use stdClass;

class Helper
{
    public static $record = null;
  
    public static function filterString($string)
    {
        return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }

    /*
     * TODO: make this better for facebook register(?)
     */
    public static function filterCharacters($getString)
    {
        $getCharacters = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-'
        );

        return strtolower(strtr($getString, $getCharacters));
    }

    public static function convertIp($ip_address)
    {
        if(!Permission::exists('housekeeping_ip_display', request()->player->rank)) {
            $regex = array("/[\d]{3}$/", "/[\d]{2}$/", "/[\d]$/");
            $replace = array("xxx", "xxx", "xxx");
            return preg_replace($regex, $replace, $ip_address);
        } else {
            return $ip_address;
        }
    }

    public static function convertSlug($string)
    {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $string);
        $slug = trim(preg_replace('~[^-\w]+~', '', $slug), '-');
        return strtolower(preg_replace('~-+~', '-', $slug));
    }

    public static function timediff($timestamp, $type = null)
    {
        Date::setLocale(Config::language);
        $convert = ($timestamp - time());
        $date = new Date(time() - $convert, Config::region);

        return $type == null ? $date->ago() : $date->timespan();
    }
  
    public static function bbCode($string, $strip = true) 
    {
        if(is_array($strip)) {
            $string = self::filterString($string);
        }
      
        $filter   = array(
            "/\[i\](.*?)\[\/i\]/is",
            "/\[b\](.*?)\[\/b\]/is",
            "/\[u\](.*?)\[\/u\]/is",
            "/\[url=(.*?)\](.*?)\[\/url\]/is",
            "/\[color=(.*?)\](.*?)\[\/color\]/is",
            "/\[size=(.*?)\](.*?)\[\/size\]/is",
            "/\[youtube\](.*?)\[\/youtube\]/is",
            "/\[sup\](.*?)\[\/sup\]/is",
            "/\[sub\](.*?)\[\/sub\]/is",
            "/\[img\](.*?)\[\/img\]/is",
            "/\[list\](.*?)\[\/list\]/is",
            "/\[quote=(.*?)\](.*?)\[\/quote\]/is"
        );
      
        $transform = array(
            "<i>$1</i>",
            "<b>$1</b>",
            "<u>$1</u>",
            "<a href=\"$1\">$2</a>",
            "<span style=\"color: $1\">$2</span>",
            "<span style=\"font-size: $1\">$2</span>",
            "<iframe class=\"youtube-player\" type=\"text/html\" width=\"640\"\ height=\"385\" src=\"https://www.youtube.com/embed/$1\" frameborder=\"0\"></iframe>",
            "<sup>$1</sup>",
            "<sub>$1</sub>",
            "<img src=\"$1\">",
            "<li></li>",
            "<blockquote><span class=\"author\">$1</span><br/>$2</blockquote>"
        );
      
        $string = preg_replace($filter, $transform, $string);
        return self::stripScript($string);
    }
  
    public static function stripScript($string) 
    {
        $string = str_replace('<script>',"&#60;script&#62;",$string);
        return str_replace('</script>',"&#60;/script&#62;",$string);
    }
  
    public static function quote($message, $topic_id)
    {
        preg_match_all('/#quote:(\w+)/', $message, $match);

        foreach($match[1] as $match) {
            $post   = Guild::getPostByTopidId($match, $topic_id);
            if(!empty($post)) {
                $quote  = "[quote=" .  Player::getDataById($post->user_id, array('username'))->username . "]" . $post->message . "[/quote]";
                $message = str_replace("#quote:" . $match, $quote, $message);
                $message = self::bbCode($message, $match);
            }
        }
      
        if (($pos = strpos($message, "#quote:")) !== FALSE) { 
            return self::quote($message, $topic_id);  
        }
        return $message;
    }

    public static function tagByUser($message)
    {
        $data = new stdClass();

        preg_match_all('/@(\w+)/', $message, $match);

        foreach($match[1] as $row) {
            $user = Player::getDataByUsername($row, array('id','username'));
            if(isset($user->id) != null) {
                $data->user[$user->id]['userid'] = $user->id;
                $userProfile = '@[url=/profile/'.$row.']' .$row . '[/url]';
                $message = str_replace("@" . $row, $userProfile, $message);
            }
        }

        return $message;
    }

    public static function slug($slug)
    {
        return explode('-', $slug)[0];
    }
  
    public static function asnBan() {
        $reader = new Reader(__DIR__. '/../' . Config::vpnLocation);

        try {
            static::$record = $reader->asn(getIpAddress());
        } catch (AddressNotFoundException $e) {
        } catch (InvalidDatabaseException $e) {
        }

        if (static::$record) {
            $asn = Ban::getNetworkBanByAsn(static::$record->autonomousSystemNumber);
            if ($asn) {
                return $asn;
            }
        }
    }
}

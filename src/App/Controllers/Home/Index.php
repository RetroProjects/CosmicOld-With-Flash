<?php
namespace Cosmic\App\Controllers\Home;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

use Cosmic\App\Config;

use Cosmic\App\Models\Community;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Core;

use Cosmic\System\ViewService;
use Cosmic\System\LocaleService;

use stdClass;

class Index
{
    public function index()
    {              
      
        $news = Community::getNews(6);
        $rooms = Community::getPopularRooms(5);
        $groups = Community::getPopularGroups(7);

        if(isset(request()->player->id)) {
            $friends = Player::getMyOnlineFriends(request()->player->id);
            $currencys = Player::getCurrencys(request()->player->id);
        }
      
        $oftw_userid = Core::Settings()->user_of_the_week ?? null;
        $oftw = Player::getDataByUsername($oftw_userid, ['username', 'look', 'motto']);

        ViewService::renderTemplate('Home/home.html', [
            'title'     => !request()->player ? LocaleService::get('core/title/home') : request()->player->username,
            'page'      => 'home',
            'rooms'     => $rooms,
            'groups'    => $groups,
            'news'      => $news,
            'oftw'      => $oftw,
            'currencys' => isset($currencys) ? $currencys : null,
            'friends'   => isset($friends) ? $friends : null
        ]);
     }
}

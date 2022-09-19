<?php
namespace Cosmic\App\Controllers\Games;

use Cosmic\App\Config;

use Cosmic\App\Models\Player;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Community;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class Ranking
{
    public function index()
    {
        $currencys = array();
        foreach(Core::getCurrencys() as $type) 
        {
            $highscores = Community::getCurrencyHighscores($type->type, 6);
            $type = $type->currency;
            
            foreach($highscores as $highscore) {
                $highscore->player = Player::getDataById($highscore->user_id, ['username', 'look']);
            }
          
            $currencys[$type] = $highscores;
        }
      
        $credits = Community::getCredits(6);
        foreach ($credits as $item) 
        {
            $item->player = Player::getDataById($item->id, array('username', 'look'));
        }
      
        $achievements = Community::getAchievement(6);
        foreach ($achievements as $item) 
        {
            $item->player = Player::getDataById($item->user_id, array('username', 'look'));
        }
     
        $respectreceived = Community::getRespectsReceived(6);
        foreach ($respectreceived as $item) 
        {
            $item->player = Player::getDataById($item->user_id, array('username', 'look'));
        }
        
        $onlinetime = Community::getOnlineTime(6);
        foreach ($onlinetime as $item) 
        {
            $item->player = Player::getDataById($item->user_id, array('username', 'look'));
        }
        
        ViewService::renderTemplate('Games/ranking.html', [
            'title' => LocaleService::get('core/title/games/ranking'),
            'page'  => 'games_ranking',
            'achievements' => $achievements,
            'credits' => $credits,
            'respects' => $respectreceived,
            'online' => $onlinetime,
            'currencys'  => $currencys
        ]);
    }
}

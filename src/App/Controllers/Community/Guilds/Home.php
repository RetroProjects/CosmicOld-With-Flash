<?php
namespace Cosmic\App\Controllers\Community\Guilds;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Guild;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class Home
{
    public function index()
    { 
        if(isset(request()->player->id)) {
            $forums = Guild::getCategory(request()->player->id);
        
            foreach($forums as $forum) {
                $forum->slug = Helper::convertSlug($forum->name);
            }
          
            $public = Guild::getPublicGuilds();

            foreach($public as $guild) {
                $guild->user = Guild::getGuilds($guild->id, request()->player->id);
                $guild->slug = Helper::convertSlug($guild->name);
            }
        }
      
        $latestPosts = Guild::latestForumPosts();
        foreach($latestPosts as $latest) {
            $latest->slug   = Helper::convertSlug($latest->subject);
            $latest->author = Player::getDataById($latest->user_id, array('username', 'look'));
        }
     
        ViewService::renderTemplate('Community/Guilds/index.html', [
            'title'   => LocaleService::get('core/title/community/forum'),
            'page'    => 'forum',
            'forums'  => $forums ?? null,
            'public'  => $public ?? null,
            'latestposts' => $latestPosts
        ]);
    }
}
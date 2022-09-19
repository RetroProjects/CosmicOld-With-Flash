<?php
namespace Cosmic\App\Controllers\Community\Guilds;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Guild;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Topic {
  
    public function __construct() 
    {
        if(!request()->guild->read_forum) {
            if(request()->isAjax()) {
                response()->json(["status" => "error", "message" => LocaleService::get('core/notification/invisible')]);
            }
          
            redirect('/guilds');
        }
    }
  
    public function index($group_id, $topic_id, $page = 1)
    {
        $topic = Guild::getTopicById(Helper::slug($topic_id));
        $guild = request()->guild;
      
        if($page == 1) {
            $posts = Guild::getPostsById($topic->id, 10);
        } else {
            $offset = ($page - 1) * 10;
            $posts = Guild::getPostsById($topic->id, 10, $offset);
        }
      
        $totalPages   = ceil(count(Guild::getPostsById($topic->id)) / 10);

        foreach($posts as $post) 
        {
            $post->author     = Player::getDataById($post->user_id, array('username', 'look', 'rank', 'account_created'));
            $post->created_at = Helper::timediff($post->created_at);
          
            $post->likes      = Guild::getPostLikes($post->id);
          
            $post->content    = Helper::bbCode(Helper::quote($post->message, $post->thread_id));
          
            foreach($post->likes as $likes) {
                $likes->user  = Player::getDataById($likes->user_id, array('username'));
            }
        }

        $topic->slug = Helper::slug(Helper::filterCharacters($guild->name));
        $topic->total = $totalPages;
        $topic->forum = $guild;
        $topic->posts = $posts;

        ViewService::renderTemplate('Community/Guilds/topic.html', [
            'title'       => $guild->name,
            'page'        => 'forum',
            'topic'       => $topic,
            'currentpage' => $page
        ]);
    }
  
    public function reply()
    {
        ValidationService::validate([
            'message'   => 'required',
            'topic_id'  => 'required|numeric',
            'guild_id'  => 'required|numeric'
        ]);
      
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        $topic    = Guild::getTopicById(input('topic_id'));
        $totalPages = ceil(count(Guild::getPostsById($topic->id)) / 10);
        $guild_id = input('guild_id');
      
        if (request()->player === null) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        if(!request()->guild->post_messages != false) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/post_not_allowed')]);
        }
      
        if($topic->locked){
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/topic_closed')]);
        }

        $reply_id = Guild::createReply(input('topic_id'), Helper::FilterString(Helper::tagByUser(input('message'))), request()->player->id); 
        response()->json(["status" => "success", "message" =>  LocaleService::get('core/notification/message_placed'), "replacepage" => "guilds/{$guild_id}/thread/{$topic->id}-" . Helper::convertSlug($topic->subject) . "/page/{$totalPages}#{$reply_id}"]);
    }
  
    public function stickyclosethread()
    {
        ValidationService::validate([
            'id'      => 'required|numeric',
            'action'  => 'required'
        ]);
      
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        if(!request()->guild->mod_forum != false) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/no_permissions')]);
        }
      
        $guild_id = input('guild_id');
      
        $topic = Guild::getTopicById($guild_id);
        $topic->slug = Helper::convertSlug($topic->subject);

        if(input()->post('action')->value == "sticky") {
            Guild::isSticky(input()->post('id')->value);
            response()->json(["status" => "success", "message" => LocaleService::get('forum/is_sticky'), "replacepage" => "guilds/{$guild_id}/thread/{$topic->id}-{$topic->slug}"]);
        }
      
        Guild::isClosed(input()->post('id')->value);
        echo '{"status":"success","message":"' . LocaleService::get('forum/is_closed') . '","replacepage":"guilds/'. $guild_id .'/thread/' . $topic->id . '-'. $topic->slug . '"}';
        exit;
    }
  
    public function like()
    {
        ValidationService::validate([
            'id'   => 'required|numeric'
        ]);
      
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        if (in_array(request()->player->id, array_column(Guild::getPostLikes(input('id')), 'user_id'))) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/already_liked')]);
        }

        Guild::insertLike(input('id'), request()->player->id);
        $topic = Guild::getTopicByPostId(input('id'));
      
        response()->json(["status" => "success", "message" => LocaleService::get('core/notification/liked'), "replacepage" => input()->post('url')->value . "#{$topic->idp}"]);
    }
}    

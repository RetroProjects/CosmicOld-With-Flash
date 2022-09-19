<?php
namespace Cosmic\App\Controllers\Community;

use Cosmic\App\Helpers\Helper;

use Cosmic\App\Controllers\Home\Profile;

use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Community;
use Cosmic\App\Models\Player;

use Cosmic\App\Library\Json;

use Cosmic\System\LocaleService;
use Cosmic\System\ValidationService;

use stdClass;

class Feeds
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function post()
    {
         ValidationService::validate([
            'reply'     => 'required|max:50',
            'userid'    => 'required'
        ]);

        $reply      = input('reply');
        $user_id    = input('userid');

        $player = Player::getDataById($user_id, 'username');
        if ($player == null) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }

        if (empty($reply) || empty($user_id)) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }

        $userposts = Community::getFeedsByUserid($user_id);
        if(!empty($userposts)) {
            if(end($userposts)->from_user_id == request()->player->id){
                response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
            }
        }

        Community::addFeedToUser(Helper::tagByUser($reply), request()->player->id, $user_id);
        response()->json(["status" => "success", "message" => LocaleService::get('core/notification/message_placed'), "replacepage" => "profile/" . $player->username]);;

    }

    public function delete()
    {
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        $feed_id = Community::getFeedsByFeedId(input('feedid'));
        if($feed_id == null) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }

        if ($feed_id->to_user_id != request()->player->id && Permission::exists('housekeeping_moderation_tools', request()->player->rank)) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }

        Community::deleteFeedById($feed_id->id);
        response()->json(["status" => "error", "success" => LocaleService::get('core/notification/message_deleted'), "replacepage" => "profile/". Player::getDataById($feed_id->to_user_id, ['username'])->username]);
    }

    public function like()
    {
         if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }

        if (Community::userAlreadylikePost(input('post'), request()->player->id)) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/already_liked')]);
        }

        Community::insertLike(input('post'), request()->player->id);
        response()->json(["status" => "success", "message" => LocaleService::get('core/notification/liked')]);
    }

    public function more()
    {
        $feeds = new Profile();
        $init = $feeds->feeds(input('count'), input('player_id'));

        echo Json::encode(['feeds' => $init]);
    }
}

<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Helpers\Helper;

use Cosmic\App\Models\Community;
use Cosmic\App\Models\Player;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;

class Feeds
{
    public function getfeeds()
    {
        $feeds = Community::getFeeds(500);
        if ($feeds == null) {
            exit;
        }

        foreach ($feeds as $row) {
            $from_user = Player::getDataById($row->from_user_id, 'username');
            $to_user = Player::getDataById($row->to_user_id, 'username');

            if ($from_user == null || $to_user == null) {
                exit;
            }

            if (isset($from_user->username)) {
                $row->from_username = $from_user->username;
            }

            if ($to_user->username) {
                $row->to_username = $to_user->username;
            }

            $row->message = Helper::filterString($row->message);
            $row->timestamp = Helper::timediff($row->timestamp);
        }

        Json::filter($feeds, 'desc', 'id');
    }

    public function deletefeed()
    {
        $feed_id = Community::getFeedsByFeedId(input()->post('post')->value);
        if (empty($feed_id)) {
            response()->json(["status" => "error", "message" => "No feeds available"]);
        }

        Community::deleteFeedById($feed_id->id);
        response()->json(["status" => "success", "message" => "Feed was successfully deleted"]);
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/Management/feeds.html', ['permission' => 'housekeeping_website_feeds']);
    }
}
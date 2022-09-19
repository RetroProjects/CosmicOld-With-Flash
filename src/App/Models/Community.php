<?php
namespace Cosmic\App\Models;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

class Community
{
    /*
     * Get photos queries
     */
  
    public static function getPhotos($limit = 10, $offset = 0)
    {
        return QueryBuilder::connection()->table('camera_web')->offset($offset)->limit($limit)->orderBy('id', 'desc')->get();
    }
  
    public static function getPhotoById($id)
    {
        return QueryBuilder::connection()->table('camera_web')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->first();
    }

    public static function getPhotosLikes($photoid)
    {
        return QueryBuilder::connection()->table('website_photos_likes')->where('photo_id', $photoid)->count();
    }
  
    public static function userAlreadylikePhoto($photoid, $userid)
    {
        return QueryBuilder::connection()->table('website_photos_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('photo_id', $photoid)->where('user_id', $userid)->count();
    }
  
    public static function getCurrencyHighscores($type, $limit) 
    {
        return QueryBuilder::connection()->table('users_currency')->selectDistinct(array('users_currency.user_id', 'users_currency.amount', 'users_currency.type'))
                      ->join('users', 'users_currency.user_id', '=', 'users.id')->where('users_currency.type', $type)
                      ->join('website_permissions_ranks', 'users.rank', '=', 'website_permissions_ranks.rank_id')->whereNot('website_permissions_ranks.permission_id', '!=', 27)
                      ->orderBy('users_currency.amount', 'DESC')->limit($limit)->get();
    }
    /*
     * Get rarevalue queries
     */
	 public static function getPageRares()
		{
           return QueryBuilder::connection()->table('website_rares_pages')->select('website_rares_pages.*')->orderBy('website_rares_pages.id', 'desc')->get();
		}
		
	public static function getPageNameFromId($id)
    {
           return QueryBuilder::connection()->table('website_rares_pages')->select('name')->where('id', $id)->first();
    }
	public static function getRareValuePagesByString($string, $limit = 10)
	{
        return QueryBuilder::connection()->table('website_rares_pages')->select('website_rares_pages.*')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('name', 'LIKE', $string . '%')->limit($limit)->get();
	}
		
	public static function getRareList($parent_id)
    {
        return QueryBuilder::connection()->table('website_rares')->select('website_rares.*')->select('users.username')->select(QueryBuilder::connection()->raw('count(items.id) as units'))->leftJoin('items', 'items.item_id', '=', 'website_rares.item_id')->leftJoin('users', 'users.id', '=', 'website_rares.last_editor')->orderBy('website_rares.id', 'desc')->where('website_rares.parent_id', $parent_id)->groupBy('website_rares.id')->get();
    }

	public static function getRareLastList($limit=10)
	{
        return QueryBuilder::connection()->table('website_rares')->select('website_rares.*')->select('users.username')->select(QueryBuilder::connection()->raw('count(items.id) as units'))->leftJoin('items', 'items.item_id', '=', 'website_rares.item_id')->leftJoin('users', 'users.id', '=', 'website_rares.last_editor')->orderBy('website_rares.id', 'desc')->groupBy('website_rares.id')->limit($limit)->get();
	}
	 
    /*
     * Get news queries
     */
    public static function getNews($limit = 10, $offset = 0)
    {
        return QueryBuilder::connection()->table('website_news')->select('website_news.*')->select('website_news_categories.category')->setFetchMode(PDO::FETCH_CLASS, get_called_class())
                  ->leftJoin('website_news_categories', 'website_news_categories.id', '=', 'website_news.category')->where('website_news.hidden', '0')->offset($offset)->limit($limit)->orderBy('website_news.timestamp', 'desc')->get();
    }

    public static function getArticleById($id)
    {
        return QueryBuilder::connection()->table('website_news')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->find($id);
    }

    public static function getPostsArticleById($id)
    {
        return QueryBuilder::connection()->table('website_news_reactions')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('news_id', $id)->get();
    }
  
    public static function getLastArticle()
    {
        return QueryBuilder::connection()->table('website_news')->select('id')->select('slug')->orderBy('id', 'desc')->first();
    }
  
    public static function latestArticleReaction($news_id)
    {
        return QueryBuilder::connection()->table('website_news_reactions')->where('news_id', $news_id)->where('hidden', '0')->orderBy('timestamp', 'desc')->first();
    }
    
    public static function isNewsHidden($news_id)
    {
        return QueryBuilder::connection()->table('website_news_reactions')->select('hidden')->where('id', $news_id)->first();
    }
  
    public static function hideNewsReaction($reaction_id, $int)
    {
        return QueryBuilder::connection()->table('website_news_reactions')->where('id', $reaction_id)->update(array('hidden' => $int));
    }
  
    public static function addNewsReaction($news_id, $player_id, $message)
    {
        $data = array(
            'news_id'     => $news_id,
            'player_id'   => $player_id,
            'message'     => $message,
            'timestamp'   => time()
        );
        return QueryBuilder::connection()->table('website_news_reactions')->insert($data);
    }
    /*
     * Feeds queries
     */
  
    public static function getFeeds($limit = 10, $offset = 0)
    {
        return QueryBuilder::connection()->table('website_feeds')->where('is_hidden', 0)->offset($offset)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public static function getFeedsByFeedId($feedid)
    {
        return QueryBuilder::connection()->table('website_feeds')->where('is_hidden', 0)->where('id', $feedid)->first();
    }
  
    public static function getFeedsByUserid($userid, $limit = 5)
    {
        return QueryBuilder::connection()->table('website_feeds')->select('website_feeds.*')->select('users.username')->select('users.look')
                ->join('users', 'website_feeds.from_user_id', '=', 'users.id')
                ->where('to_user_id', $userid)->where('is_hidden', 0)
                ->orderBy('id', 'desc')->limit($limit)->get();
    }

    public static function getFeedsByUserIdOffset($offset, $user_id, $limit = 10)
    {
        return QueryBuilder::connection()->table('website_feeds')->where('is_hidden', 0)->offset($offset)->where('to_user_id', $user_id)->orWhere('from_user_id', $user_id)->orderBy('id', 'desc')->limit($limit)->get();
    }


    public static function deleteFeedById($id)
    {
        QueryBuilder::connection()->table('website_feeds')->where('id', $id)->delete();
        QueryBuilder::connection()->table('website_feeds_reactions')->where('feed_id', $id)->delete();
    }

    public static function getLikes($feedid)
    {
        return QueryBuilder::connection()->table('website_feeds_likes')->where('feed_id', $feedid)->count();
    }

    public static function userAlreadylikePost($feedid, $userid)
    {
        return QueryBuilder::connection()->table('website_feeds_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('feed_id', $feedid)->where('user_id', $userid)->count();
    }
  
  
    /*
     * Insert queries
     */
  
    public static function insertLike($feedid, $userid)
    {
        $data = array(
            'feed_id'     => $feedid,
            'user_id'     => $userid
        );

        return QueryBuilder::connection()->table('website_feeds_likes')->insert($data);
    } 
  
    public static function insertPhotoLike($photoid, $userid)
    {
        $data = array(
            'photo_id'     => $photoid,
            'user_id'     => $userid
        );

        return QueryBuilder::connection()->table('website_photos_likes')->insert($data);
    } 

    public static function addFeedToUser($message, $userid, $from)
    {
        $data = array(
            'to_user_id'  => $from,
            'message'     => $message,
            'timestamp'   => time(),
            'from_user_id' => $userid
        );
        return QueryBuilder::connection()->table('website_feeds')->insert($data);
    }

    public static function getPopularRooms($limit = 10, $offset = 0)
    {
        return QueryBuilder::connection()->table('rooms')->offset($offset)->limit($limit)->orderBy(['rooms.users', 'rooms.id'], 'desc')->get();
    }

    public static function getPopularGroups($limit = 10, $offset = 0)
    {
        return QueryBuilder::connection()->query("SELECT guilds.name, guilds.badge, guilds.description, guilds_members.id, COUNT(guild_id) AS Total FROM guilds_members JOIN guilds ON guilds_members.guild_id = guilds.id AND guilds_members.level_id < 3 GROUP BY guilds_members.guild_id ORDER BY Total DESC LIMIT " . $limit)->get();
    }
  
    public static function getRandomUsers($limit)
    {
        return QueryBuilder::connection()->query('SELECT username, look FROM users ORDER BY RAND() LIMIT  ' . $limit)->get();
    }
  
    public static function getAchievement($limit = 10)
    {
        return QueryBuilder::connection()->table('users_settings')->select('user_id')->select('achievement_score')->orderBy('achievement_score', 'desc')
                ->join('users', 'users_settings.user_id', '=', 'users.id')
                ->join('website_permissions_ranks', 'users.rank', '=', 'website_permissions_ranks.rank_id')->whereNot('website_permissions_ranks.permission_id', '!=', 27)->limit($limit)->get();
    }
  
    public static function getRespectsReceived($limit = 10)
    {
        return QueryBuilder::connection()->table('users_settings')->select('user_id')->select('respects_received')->orderBy('respects_received', 'desc')
                ->join('users', 'users_settings.user_id', '=', 'users.id')
                ->join('website_permissions_ranks', 'users.rank', '=', 'website_permissions_ranks.rank_id')->whereNot('website_permissions_ranks.permission_id', '!=', 27)->limit($limit)->get();
    }
    
    public static function getOnlineTime($limit = 6)
    {
        return QueryBuilder::connection()->table('users_achievements')->select('users_achievements.*')
                ->join('users', 'users_achievements.user_id', '=', 'users.id')
                ->where('users_achievements.achievement_name', 'AllTimeHotelPresence')
                ->orderBy('progress', 'desc')->limit($limit)->get();
    }

    public static function getCredits($limit = 10)
    {
        return QueryBuilder::connection()->table('users')->select('users.id')->select('users.credits')->orderBy('users.credits', 'desc')
                ->join('website_permissions_ranks', 'users.rank', '=', 'website_permissions_ranks.rank_id')->whereNot('website_permissions_ranks.permission_id', '!=', 27)->limit($limit)->get();
    }
 
    /*
     * Jobs queries
     */
  
    public static function getJobs()
    {
        return QueryBuilder::connection()->table('website_jobs')->orderBy('id', 'DESC')->get();
    }
  
    public static function getJob($id)
    {
        return QueryBuilder::connection()->table('website_jobs')->where('id', $id)->first();
    }
  
    public static function getJobApplications($id)
    {
        return QueryBuilder::connection()->table('website_jobs')->join('website_jobs_applys', 'website_jobs.id', '=', 'website_jobs_applys.job_id')->get();
    }
  
    public static function getJobApplication($job_id, $user_id)
    {
        return QueryBuilder::connection()->table('website_jobs_applys')->where('job_id', $job_id)->where('user_id', $user_id)->first();
    }
  
    public static function getApplicationById($id)
    {
        return QueryBuilder::connection()->table('website_jobs_applys')->where('id', $id)->first();
    }
  
    public static function getMyJobApplication($user_id)
    {
        return QueryBuilder::connection()->table('website_jobs_applys')->join('website_jobs', 'website_jobs_applys.job_id', '=', 'website_jobs.id')->where('user_id', $user_id)->get();
    }
  
    public static function getAllApplications($id)
    {
        return QueryBuilder::connection()->table('website_jobs_applys')->where('job_id', $id)->get();
    }
  
    public static function addJobApply($job_id, $player_id, $firstname, $message, $available_monday, $available_tuesday, $available_wednesday, $available_thursday, $available_friday,$available_saturday, $available_sunday)
    {
        $data = array(
            'job_id'                =>  $job_id,
            'user_id'               =>  $player_id,
            'firstname'             =>  $firstname,
            'message'               =>  $message,
            'available_monday'      =>  $available_monday,
            'available_tuesday'     =>  $available_tuesday,
            'available_wednesday'   =>  $available_wednesday,
            'available_thursday'    =>  $available_thursday,
            'available_friday'      =>  $available_friday,
            'available_saturday'    =>  $available_saturday,
            'available_sunday'      =>  $available_sunday,
            'status'                =>  "open",
        );
        return QueryBuilder::connection()->table('website_jobs_applys')->insert($data);
    }
}

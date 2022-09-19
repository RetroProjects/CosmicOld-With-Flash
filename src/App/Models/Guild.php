<?php
namespace Cosmic\App\Models;

use Cosmic\System\DatabaseService as QueryBuilder;
use PDO;

class Guild
{
    public static function getCategory($user_id)
    {
        return QueryBuilder::connection()->table('guilds_members')->join('guilds', 'guilds_members.guild_id', '=', 'guilds.id')->where('guilds_members.user_id', $user_id)->where('guilds.forum', '1')->get();
    }
  
    public static function getForums($guild_id)
    {
        return QueryBuilder::connection()->table('guilds_forums_threads')->where('guilds_forums_threads.guild_id', $guild_id)->get();
    }
  
    public static function getGuild($guild_id) 
    {
        return QueryBuilder::connection()->table('guilds')->where('id', $guild_id)->first();
    }
  
    public static function getPublicGuilds() 
    {
        return QueryBuilder::connection()->table('guilds')->where('read_forum', 'EVERYONE')->where('forum', '=', '1')->get();
    }
  
    public static function getGuilds($guild_id, $user_id) 
    {
        return QueryBuilder::connection()->table('guilds')->select('guilds_members.*')->join('guilds_members', 'guilds.id', '=', 'guilds_members.guild_id')->where('guilds.id', $guild_id)->where('guilds_members.user_id', $user_id)->first();
    }
  
    public static function getForumTopics($guild_id, $limit = 1000, $offset = 0)
    {
        return QueryBuilder::connection()->table('guilds_forums_threads')->orderBy('pinned', 'DESC')->where('guild_id', $guild_id)->offset($offset)->limit($limit)->where('state', '0')->orderBy('id', 'DESC')->get();
    }
  
    public static function getPostsById($id, $limit = 1000, $offset = 0)
    {
        return QueryBuilder::connection()->table('guilds_forums_comments')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('thread_id', $id)->offset($offset)->limit($limit)->get();
    }
  
    public static function getLatestForumPost($topicid)
    {
        return QueryBuilder::connection()->table('guilds_forums_comments')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('thread_id', $topicid)->orderBy('id', 'DESC')->first();
    } 
  
    public static function getPostByTopidId($id, $topicid)
    {
        return QueryBuilder::connection()->table('guilds_forums_comments')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->where('thread_id', $topicid)->first();
    } 
  
    public static function getForumLatestTopic($guild_id)
    {
        return QueryBuilder::connection()->table('guilds_forums_threads')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('guild_id', $guild_id)->orderBy('created_at', 'DESC')->limit(1)->first();
    }
  
    public static function getPostLikes($postid)
    {
        return QueryBuilder::connection()->table('website_forum_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('post_id', $postid)->get();
    }
  
    public static function getTopicById($id)
    {
        return QueryBuilder::connection()->table('guilds_forums_threads')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('id', $id)->first();
    }
  
    public static function getTopicByPostId($postid)
    {
        return QueryBuilder::connection()->table('guilds_forums_comments')->select('guilds_forums_threads.*')->select(QueryBuilder::connection()->raw('guilds_forums_comments.id as idp'))
                  ->join('guilds_forums_threads', 'guilds_forums_comments.thread_id', '=', 'guilds_forums_threads.id')
                  ->setFetchMode(PDO::FETCH_CLASS, get_called_class())->where('guilds_forums_comments.id', $postid)->first();
    }
  
    public static function latestForumPosts($limit = 5) 
    {
        return QueryBuilder::connection()->table('guilds_forums_comments')->select('users.username')->select('guilds_forums_threads.subject')
                    ->select('guilds_forums_threads.guild_id')->select('users.look')
                    ->select('guilds_forums_comments.user_id')->select('guilds_forums_comments.created_at')->select('guilds_forums_comments.id')->select('guilds_forums_comments.thread_id')
                    ->select('guilds_forums_comments.created_at')
                    ->setFetchMode(PDO::FETCH_CLASS, get_called_class())->orderBy('guilds_forums_comments.created_at', 'DESC')->limit($limit)
                    ->join('guilds_forums_threads', 'guilds_forums_comments.thread_id', '=', 'guilds_forums_threads.id')
                    ->join('users', 'guilds_forums_comments.user_id',  '=', 'users.id')->get();
    }
  
    public static function createTopic($guild_id, $title, $userid, $slug)
    {
        $data = array(
            'guild_id'    => $guild_id,
            'opener_id'   => $userid,
            'subject'     => $title,
            'created_at'  => time()
        );
      
        return QueryBuilder::connection()->table('guilds_forums_threads')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    } 
  
    public static function createReply($thread_id, $content, $userid)
    {
        $data = array(
            'thread_id'   => $thread_id,
            'user_id'     => $userid,
            'created_at'  => time(),
            'message'     => $content
        );
      
        return QueryBuilder::connection()->table('guilds_forums_comments')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    } 
  
    public static function insertLike($postid, $userid)
    {
        $data = array(
            'post_id' => $postid,
            'user_id' => $userid
        );
      
        return QueryBuilder::connection()->table('website_forum_likes')->setFetchMode(PDO::FETCH_CLASS, get_called_class())->insert($data);
    } 
  
    public static function updatePostByid($content, $postid)
    {
        $data = array(
            'content'     => $content,
            'updated_at'  => time(),
        );
        return QueryBuilder::connection()->table('guilds_forums_comments')->where('id', $postid)->update($data);
    }  
  
    public static function isSticky($topicid)
    {
        return QueryBuilder::connection()->query('UPDATE guilds_forums_threads SET pinned = 1 - pinned WHERE id = "'. $topicid .'"');
    }
  
    public static function isClosed($topicid)
    {
        return QueryBuilder::connection()->query('UPDATE guilds_forums_threads SET locked = 1 - locked WHERE id = "'. $topicid .'"');
    }
}

<?php
namespace Cosmic\App\Middleware;

use Cosmic\App\Models\Guild;
use Cosmic\App\Models\Player;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class GuildMiddleware implements IMiddleware
{
    public static $permission = 3;

    public $member;
    public $guild;
  
    public $state = array(
      "OWNER"     => 0,
      "ADMINS"    => 1,
      "MEMBERS"   => 2,
      "EVERYONE"  => 3
    );
  
    public function handle(Request $request) : void
    {
        $param = !empty(input()->post('guild_id')->value) ? input()->post('guild_id')->value : explode("/", url()->getOriginalUrl())[2];
        $guild = Guild::getGuild(explode('-', $param)[0]);

        if($guild !== null) {

            /* Set guild */
            $this->guild = $guild;
          
            /* Get member from guild */
            $this->member = Guild::getGuilds($guild->id, request()->player->id);
            if(empty($this->member)) {
                $this->member = new \stdClass();
                $this->member->level_id = self::$permission;
            }

            if($this->guild->user_id != request()->player->id) {

                /* Set permission */
                self::$permission = $this->guild->read_forum;

                /* Permissions read forum */
                if($this->state[self::$permission] == 1 && !$this->isAdmin()) {
                    $this->guild->read_forum = false;
                } else if ($this->state[self::$permission] == 2 && !$this->isAdmin() && !$this->isStaff()) {
                    $this->guild->read_forum = false;
                }

                /* Set permission */
                self::$permission = $this->guild->post_messages;

                /* Permission post messages */
                if ($this->state[self::$permission] == 0 && $this->guild->user_id != request()->player->id) {
                    $this->guild->post_messages = false;
                } else if($this->state[self::$permission] == 1 && !$this->isAdmin()) {
                    $this->guild->post_messages = false;
                } else if ($this->state[self::$permission] == 2 && !$this->isAdmin() && !$this->isStaff()) {
                    $this->guild->post_messages = false;
                } else if ($this->state[self::$permission] == 3 && $this->guild->user_id != request()->player->id && !$this->isAdmin()) {
                    $this->guild->post_messages = false;
                } 

                /* Set permission */
                self::$permission = $this->guild->post_threads;

                /* Permission post threads */
                if ($this->state[self::$permission] == 0 && $this->guild->user_id != request()->player->id) {
                    $this->guild->post_threads = false;
                } else if($this->state[self::$permission] == 1 && !$this->isAdmin()) {
                    $this->guild->post_threads = false;
                } else if ($this->state[self::$permission] == 2 && !$this->isAdmin() && !$this->isStaff()) {
                    $this->guild->post_threads = false;
                } else if ($this->state[self::$permission] == 3 && $this->guild->user_id != request()->player->id && !$this->isAdmin()) {
                    $this->guild->post_threads = false;
                } 

                /* Set permission */
                self::$permission = $this->guild->mod_forum;

                 /* Permission mod tools */
                if ($this->state[self::$permission] == 0 && $this->guild->user_id != request()->player->id) {
                    $this->guild->mod_forum = false;
                } else if ($this->state[self::$permission] == 3 && $this->guild->user_id != request()->player->id && !$this->isAdmin()) {
                    $this->guild->mod_forum = false;
                } else if (!$this->isAdmin() && !$this->isStaff()) {
                    $this->guild->mod_forum = false;
                } 
            }
            $request->guild = $this->guild;
        } else {
            redirect('/guilds');
        }
    }
  
    public function isAdmin() 
    {
        return $this->member != null && ($this->member->level_id <= $this->state[self::$permission] || $this->guild->user_id == request()->player->id);
    }
  
    public function isStaff() 
    {
        return Player::hasPermission('acc_modtool_ticket_q');
    }
}
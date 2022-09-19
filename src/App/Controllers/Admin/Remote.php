<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Config;
use Cosmic\App\Helpers\Helper;

use Cosmic\App\Models\Ban;
use Cosmic\App\Models\Log;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\PlayerStats;
use Cosmic\App\Models\Room;
use Cosmic\App\Library\Json;
use Cosmic\App\Library\HotelApi;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

use stdClass;

class Remote
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function user()
    {
        $username = explode("/", url()->getOriginalUrl())[5];
        if($username == null) {
            redirect('/');
            exit;
        }

        $this->user = Player::getDataByUsername($username);

        if (!isset($this->user)) {
            redirect('/housekeeping/remote/control');
        }

        $this->data->user               = (object)$this->user->username;

        $this->data->user->ip_current   = Helper::convertIp($this->user->ip_current);
        $this->data->user->ip_register  = Helper::convertIp($this->user->ip_register);
        $this->data->user->last_login   = $this->user->online ? 'Online' : date("d-m-Y H:i:s", $this->user->last_login);

        $this->data->user->id           = $this->user->id;
        $this->data->user->username     = $this->user->username;
        $this->data->user->rank_id      = $this->user->rank;
        $this->data->user->extra_rank   = $this->user->extra_rank;
        $this->data->user->mail         = $this->user->mail;
        $this->data->user->motto        = Helper::filterString($this->user->motto);
        $this->data->user->credits      = $this->user->credits;
      
        $this->data->user->currencys     = Player::getCurrencys($this->user->id);

        if(Permission::exists('housekeeping_ranks', request()->player->rank)) {
            $this->data->hotel_ranks     = Permission::getRanks(true);
            $this->data->teams           = Permission::getTeams();
        }

        if ($this->user->rank >= request()->player->rank) {
            Log::addStaffLog($this->user->id, 'No permissions for Remote Control', request()->player->id, 'error');
            redirect('/housekeeping');
        }
      
        $log = isset($type) && !empty($type) ? $type : 'All user information';
        Log::addStaffLog($this->user->id, 'Checked ' . $log, request()->player->id, 'check');

        $this->template();
    }

    public function template()
    {
        ViewService::renderTemplate('Admin/Tools/remote.html', [
            'permission' => 'housekeeping_remote_control',
            'data' => $this->data
        ]);
    }

    public function view()
    {
        $this->data->alertmessages = Admin::getAlertMessages();
        $this->data->banmessages = Admin::getBanMessages();
        $this->data->bantime = Admin::getBanTime(request()->player->rank);

        ViewService::renderTemplate('Admin/Tools/search.html', ['data' => $this->data, 'permission' => 'housekeeping_remote_control']);
    }

    public function manage()
    {
        ValidationService::validate([
            'element'  => 'required|min:1'
        ]);

        $player = Player::getDataByUsername(input()->post('element')->value, 'username');
        response()->json(["location" => "/housekeeping/remote/user/view/{$player->username}"]);
    }

    public function reset()
    {
        ValidationService::validate([
            'element'   => 'required',
            'type'      => 'required'
        ]);    
        
        $player = Player::getDataByUsername(input('element'));

        if(!Permission::exists('housekeeping_reset_user', request()->player->rank)) {
            Log::addStaffLog($player->id, 'No permissions to reset', request()->player->id, 'error');
            response()->json(["status" => "error", "message" => "No permissions to reset!"]);
        }
      
        
  
        switch (input()->post('type')->value) {
            
            case 1:
            
                Log::addStaffLog($player->id, 'Reset motto', request()->player->id, 'reset');
                HotelApi::execute('setmotto', ['user_id' => $player->id, 'motto' => 'Onacceptabel voor het Hotel Management']);
            
                response()->json(["status" => "success", "message" => "The motto of {$player->username} is resetted!"]);

                break;

            case 2: 
                HotelApi::execute('updateuser', ['user_id' => $player->id, 'look' => "hr-802-37.hd-185-1.ch-804-82.lg-280-73.sh-3068-1408-1408.wa-2001"]);
                response()->json(["status" => "success", "message" => "The look of {$player->username} is resetted!"]);

                break;
        }
    }

    public function alert()
    {
        ValidationService::validate([
            'element'   => 'required',
            'action'    => 'required'
        ]);

        $player = Player::getDataByUsername(input()->post('element')->value, array('id', 'username', 'online'));
      
        if(!Permission::exists('housekeeping_alert_user', request()->player->rank)) {
            Log::addStaffLog($player->id, 'No permissions to send alert', request()->player->id, 'error');
            response()->json(["status" => "error", "message" => "You have no permissions!"]);
        }

        $alert_message = Admin::getAlertMessagesById(input()->post('reason')->value);

        if (!$player->online) {
            response()->json(["status" => "error", "message" => "This user is offline!"]);
        }

        switch (input()->post('action')->value) {
            case 1:
                HotelApi::execute('disconnect', ['user_id' => $player->id]);
                break;

            case 2:
                HotelApi::execute('muteuser', ['user_id' => $player->id, 'duration' => 600]);
                break;
        }

        HotelApi::execute('alertuser', ['user_id' => $player->id, 'message' => $alert_message->message]);
        Log::addStaffLog($player->id, 'Alert send: ' . $alert_message->message, request()->player->id, 'alert');
        response()->json(["status" => "success", "message" => "The user {$player->username} received a alert!"]);
    }

    public function ban()
    {
        ValidationService::validate([
            'element'   => 'required',
            'reason'    => 'required',
            'type'      => 'required',
        ]);

        $player = Player::getDataByUsername(input()->post('element')->value, array('id', 'username', 'ip_current'));

        $ban_message = Admin::getBanMessagesById(input()->post('reason')->value);
        $ban_time = Admin::getBanTimeById(input()->post('expire')->value);
        
        Ban::insertBan($player->id, $player->ip_current, request()->player->id, time() + $ban_time->seconds, $ban_message->message, (input()->post('type')->value == "ip") ? "ip" : "account");
        
        HotelApi::execute('disconnect', ['user_id' => $player->id]);
        response()->json(["status" => "success", "message" => "The user {$player->username} is been banned: {$ban_time->message}"]);
    }
  
    public function getplayer()
    {
        $player_id = input()->post('user_id')->value;

        $this->getChatLogs($player_id);
        $this->getUserLogs($player_id);
        $this->getClones($player_id);
        $this->getRoomLogs($player_id);
        $this->getTradeLogs($player_id);
        $this->getMailLogs($player_id);
        $this->getBanLogs($player_id);
        $this->getStaffLogs($player_id);
        $this->getCommandLogs($player_id);

        Json::encode($this->data);
    }
  
    protected function getCommandLogs($player_id)
    {
        $this->data->commandlogs = Admin::getCommandLogsByPlayer($player_id);

        foreach ($this->data->commandlogs as $row) {
            $row->username = Player::getDataById($row->user_id, 'username')->username ?? null;
            $row->timestamp = date("d-M-Y H:i:s", $row->timestamp);
        }
    }
       

    protected function getStaffLogs($player_id)
    {
        $this->data->stafflogs = Admin::getStaffLogsByPlayerId($player_id, 3000);

        if($this->data->stafflogs !== null)
          
            foreach ($this->data->stafflogs as $logs) {
              
                $logs->username = Player::getDataById($logs->player_id, 'username')->username;
                $logs->timestamp = date("d-m-Y H:i:s", $logs->timestamp);
              
                if (is_numeric($logs->target)) {
                    $logs->target = Player::getDataById($logs->target, 'username')->username ?? null;
                }
              
            }
    }

    protected function getMailLogs($player_id)
    {
        $this->data->maillogs = Admin::getMailLogs($player_id);
      
        foreach ($this->data->maillogs as $row) {
            $row->ip_address = Helper::convertIp($row->ip_address);
            $row->timestamp = date("d-m-Y H:i:s", $row->timestamp);
        }
    }

    protected function getRoomLogs($player_id)
    {
        $this->data->rooms = Room::getByPlayerId($player_id);

        foreach($this->data->rooms as $room) {
            $room->name = Helper::filterString($room->name);
            $room->description = Helper::filterString($room->description);
        }
    }

    protected function getTradeLogs($player_id)
    {
        $this->data->tradelogs = Admin::getTradeLogs($player_id);

        foreach($this->data->tradelogs as $item) {
            $item->user_one_id = Player::getDataById($item->user_one_id, ['username']);
            $item->user_two_id = Player::getDataById($item->user_two_id, ['username']);
          
            $item->items = Admin::getTradeLogItems($item->id);
          
            foreach($item->items as $trade) {
                $trade->user_id = Player::getDataById($trade->user_id, ['username']);
            }
          
            $item->timestamp = date("d-m-Y H:i:s", $item->timestamp);
        }
    }

    protected function getChatLogs($player_id)
    {
        $this->data->chatlogs = Admin::getChatLogs($player_id);
        
        foreach ($this->data->chatlogs as $logs) {
            
            if(!isset($logs->user_to_id)) {
               $logs->user_to_id = "deleted";   
            }
            
            $logs->message = Helper::filterString($logs->message);
            $logs->timestamp = date("d-m-Y H:i:s", $logs->timestamp);
          
            if($logs->user_to_id != 0) {
                $logs->message = '<b>' . Player::getDataById($logs->user_to_id, ['username'])->username . '</b>: ' . $logs->message;
            }
        }
    }

    protected function getUserLogs($player_id)
    {
        $this->data->userlogs = Admin::getNameChangesById($player_id);
      
        foreach ($this->data->userlogs as $logs) {
            $logs->timestamp = date("d-m-Y H:i:s", $logs->timestamp);
        }
    }

    protected function getClones($player_id)
    {
        $userObject = Player::getDataById($player_id);
        $this->data->duplicateUsers = Admin::getClones($userObject->ip_current, $userObject->ip_register);
      
        foreach ($this->data->duplicateUsers as $row) {
            $row->iplast = Helper::convertIp($row->ip_current);
            $row->ipreg = Helper::convertIp($row->ip_register);
            $row->last_login = $row->online ? 'Online' : date("d-m-Y H:i:s", $row->last_login);
        }
    }

    protected function getMessengerLogs($player_id)
    {
        $this->data->messengerlogs = Admin::getMessengerLogs($player_id);
      
        foreach($this->data->messengerlogs as $row) {
            $row->message   = Helper::filterString($row->message);
            $row->timestamp  = date("d-m-Y H:i:s", $row->timestamp);
        }
    }

    protected function getBanLogs($player_id) {
        $this->data->banlog = Admin::getBanLogByUserId($player_id);
      
        foreach($this->data->banlog as $ban) {
            $ban->user_staff_id = Player::getDataById($ban->user_staff_id, ['username']);
            $ban->ban_expire = date("d-m-Y H:i:s", $ban->ban_expire);
        }
    }

    public function unban($id)
    {
        if (empty(Ban::getBanById($id))) {
            response()->json(["status" => "error", "message" => "This player is does not exists!"]);
        }

        Admin::deleteBan($ban);
        response()->json(["status" => "error", "message" => "This player is unbanned!"]);
    }

    public function change()
    {
        ValidationService::validate([
            'pincode'       => 'max:6|numeric',
            'motto'         => 'max:70'
        ]);

        $player = Player::getDataById(input()->post('user_id')->value);

        if(empty($player)) {
            response()->json(["status" => "error", "message" => "Player doesnt exist!"]);
        }

        $email = (input()->post('email')->value ? input()->post('email')->value : $player->mail);
        $pin_code = (input()->post('pincode')->value ? input()->post('pincode')->value : (string)$player->pincode);
        $motto = (input()->post('motto')->value ? input()->post('motto')->value : $player->motto);
        $rank = (input()->post('rank')->value ? input()->post('rank')->value : (string)$player->rank);
        $credits = (input()->post('credits')->value ? input()->post('credits')->value : (string)$player->credits);
        $extra_rank = (input()->post('extra_rank')->value ? input()->post('extra_rank')->value : null);
      
        $currencys = Player::getCurrencys($player->id);
 
        foreach($currencys as $currency) {
            if($currency) {
                $currency->oldamount = $currency->amount;
                $currency->amount = (int)(input()->post($currency->type)->value ? input()->post($currency->type)->value : (string)$currency->amount);
            }
        }

        if(Permission::exists('housekeeping_change_email', request()->player->rank)) {
            ValidationService::validate([
                'email' => 'required|min:6|max:72|email'
            ]);
        }

        if(Permission::exists('housekeeping_ranks',  request()->player->rank)) {
            ValidationService::validate([
                'rank' => 'required|numeric',
            ]);

            foreach($currencys as $currency) {
                if($currency && !is_int($currency->amount)) {
                    response()->json(["status" => "error", "message" => "Currency must be numeric!"]);
                }
            }
        }
        
        if (Admin::changePlayerSettings($email ?? $player->mail, $motto, $pin_code, $player->id, $extra_rank)) {

            if($player->credits != $credits) {
                HotelApi::execute('givecredits', ['user_id' => $player->id, 'credits' => - $player->credits + $credits]);
            }

            if($player->rank != $rank) {
                HotelApi::execute('setrank', ['user_id' => $player->id, 'rank' => $rank]);
            }

            foreach($currencys as $currency) {
                if($currency) {
                    if ($currency->oldamount != $currency->amount) {
                        HotelApi::execute('givepoints', ['user_id' => $player->id, 'points' => - $currency->oldamount + $currency->amount, 'type' => $currency->type]);
                    } 
                }
            }
          
            Log::addStaffLog($player->id, 'User Info saved', request()->player->id, 'MANAGE');
            response()->json(["status" => "success", "message" => "Info of {$player->username} is updated!"]);
        }
    }
}

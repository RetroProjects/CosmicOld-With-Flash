<?php
namespace Cosmic\App\Controllers;

use Cosmic\App\Config;
use Cosmic\App\Hash;
use Cosmic\System\TokenService;

use Cosmic\App\Models\Core;
use Cosmic\App\Models\Room;
use Cosmic\App\Models\Player;

use Cosmic\App\Library\HotelApi;
use Cosmic\App\Library\FindRetros;

use Cosmic\System\SessionService;
use Cosmic\System\LocaleService;

class Api
{
    public $settings;

    public function __construct()
    {
        $this->settings = Core::settings();
    }
  
    public function ssoTicket()
    {
        if(!request()->player) {
            response()->json([
                'error' => 'Required login',
            ]);
        }
      
        $user = Player::getDataById(request()->player->id);
      
        $auth_ticket = TokenService::authTicket(request()->player->id);
        Player::update(request()->player->id, ["auth_ticket" => $auth_ticket]);

        if ($user->getMembership()) {
            HotelApi::execute('setrank', ['user_id' => $user->id, 'rank' => $user->getMembership()->old_rank]);
            $user->deleteMembership();
        }
      
        if(!empty($auth_ticket)) {
            response()->json([
                "status"  => "success",  
                "ticket" => $auth_ticket
            ]);
        }
    }
  
    public function vote()
    {
          $FindRetros = new FindRetros();

          if($FindRetros->hasClientVoted()) {  
              $this->callback = ["status" => "voted"];
          } else {
              $this->callback = [
                  "status"  => 0,
                  "api"     => $FindRetros->redirectClient()
              ];
          }
      
          response()->json($this->callback ?? null);
    }
  
    public function room($roomId)
    {
        if (!request()->player->online) {
            response()->json([
                "status"      => "success",  
                "replacepage" => "hotel?room=" . $roomId
            ]);
        }

        $room = Room::getById($roomId);
        if ($room == null) {
            response()->json([
                "status" => "error", 
                "message" => LocaleService::get('core/notification/room_not_exists')
              ]);
        }

        HotelApi::execute("forwarduser", [
          "user_id" => request()->player->id, 
          "room_id" => $roomId
        ]);
      
        response()->json([
            "status" => "success",  
            "replacepage" => "hotel"
        ]);
    }

    public function user($username)
    {
        $user = Player::getDataByUsername($username);
        if(!$user) {
            response()->json([
                'error' => 'User not found'
            ]);
        }

        $response = [
            'username'  => $user->username,
            'motto'     => $user->motto,
            'credits'   => $user->credits,
            'look'      => $user->look
        ];

        
        foreach(Player::getCurrencys($user->id) as $value) {
            $response[$value->currency] = $value->amount;
        }
      
        response()->json($response);
    }
  
    public function online()
    {
        echo Core::getOnlineCount();
    }
  
    public function currencys() 
    {
        response()->json(Core::getCurrencys());
    }
}

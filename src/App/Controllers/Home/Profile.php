<?php
namespace Cosmic\App\Controllers\Home;

use Cosmic\App\Config;
use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Community;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Profiles;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

use stdClass;

class Profile
{
    private $myWidgets = [];
  
    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function profile($username)
    {
        if($username == null) {
            redirect('/');
            exit;
        }

        $player = Player::getDataByUsername($username);
        if($player == null) {
            redirect('/');
            exit;
        }
      

        $this->data->player = $player;
        $this->data->player->last_online = $this->data->player->last_online;
        $this->data->player->settings = Player::getSettings($player->id);

        $this->data->player->badges = Player::getBadges($player->id);
        $this->data->player->friends = Player::getFriends($player->id);

        $this->data->player->groups = Player::getGroups($player->id);
        $this->data->player->rooms = Player::getRooms($player->id);
        $this->data->player->photos = Player::getPhotos($player->id);

        $this->data->player->badgeCount = count($this->data->player->badges);
        $this->data->player->friendCount = count($this->data->player->friends);
        $this->data->player->groupCount = count($this->data->player->groups);
        $this->data->player->roomCount = count($this->data->player->rooms);
        $this->data->player->photoCount = count($this->data->player->photos);

        $this->data->player->feeds = Community::getFeedsByUserid($player->id);
        $this->data->player->feedCount = count($this->data->player->feeds);
        $this->data->player->feedCountTotal = count($this->data->player->feeds);
      
        $this->data->player->widgets = Profiles::getWidgets($player->id);
        $this->data->player->background = Profiles::getBackground($player->id);
        $this->data->player->notes = Profiles::getNotes($player->id);
      
        foreach ($this->data->player->feeds as $row) {
            $row->likes = Community::getLikes($row->id);
        }

        $this->template();
    }

    public function feeds($offset = null, $user_id = null)
    {
        $feeds = Community::getFeedsByUserIdOffset($offset, $user_id, 6);

        foreach ($feeds as $row) {
            $from_user = Player::getDataById($row->from_user_id, array('username','look'));
            $row->from_username = $from_user->username;
            $row->figure = $from_user->look ?? null;
            $row->likes = Community::getLikes($row->id);
            $row->message = Helper::bbCode($row->message);
        }

        return $feeds;
    }

    public function search()
    {
        if(!Player::exists(input('search'))) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/profile_notfound')]);
        }

        response()->json(["replacepage" => "profile/" . input('search')]);
    }
  
    public function store()
    {
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
        
        if(input('data') == "w") {
            
            $widgets = explode(";", Core::settings()->available_profile_widgets);
 
            foreach($widgets as $widget) {
                if(!Profiles::hasWidget(request()->player->id, $widget)) {
                    $myWidgets[] = $widget;
                }
            }
          
            if(input()->post('type')->value == "p") {
                Profiles::insert(request()->player->id, input()->post('add')->value, '0', '0', 'default_skin', input('data'));
                response()->json(["status" => "success", "replacepage" => "/profile/" . request()->player->username ]);
            }
        }

        $categorys = Profiles::getCategorys();
        $items = Profiles::getItems(input('data'));

        response()->json(["items" => $items, "categorys" => $categorys, "widgets" => $myWidgets ?? null]);
    }
  
    public function remove() 
    {
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        Profiles::remove(request()->player->id, input('id'), input('type'));
        response()->json(["status" => "success", "message" => "Widget deleted!"]); 
    }

    public function save()
    {
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        $items = json_decode(input()->post('draggable')->value);
        foreach($items as $i => $v){
            if(Profiles::hasWidget(request()->player->id, $v[0])) {
                Profiles::update(request()->player->id, $v[0], $v[1], $v[2], $v[3], $v[4]);
            } else {
                Profiles::insert(request()->player->id, $v[0], $v[1], $v[2], $v[3], $v[4]);
            }
        }
      
        if(Profiles::hasBackground(request()->player->id, input('background'))) {
            Profiles::saveBackground(request()->player->id, input('background'));
        } else {
            Profiles::insertBackground(request()->player->id, input('background'));
        }
      
        response()->json(["status" => "success", "message" => "Homepage successfully saved."]);
    }
  
    public function template()
    {
        ViewService::renderTemplate('Home/profile.html', [
         'title' => $this->data->player->username,
         'page'  => 'profile',
         'data'  => $this->data,
        ]);
    }
}

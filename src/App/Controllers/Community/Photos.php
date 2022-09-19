<?php
namespace Cosmic\App\Controllers\Community;

use Cosmic\App\Core;
use Cosmic\App\Models\Community;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

use stdClass;

class Photos
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function like()
    {
        if(!request()->player->id) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
      
        if (Community::userAlreadylikePhoto(input('post'), request()->player->id)) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/already_liked')]);
        }

        Community::insertPhotoLike(input('post'), request()->player->id);
        response()->json(["status" => "success", "message" => LocaleService::get('core/notification/liked')]);
    }

    public function more()
    {
        $this->index(input('offset'), true);
        response()->json(['photos' => $this->data->photos]);
    }

    public function index($offset = 0, $request = false)
    {
        if(is_array($offset)) {
            $photos = Community::getPhotos(12);
        } else {
            $photos = Community::getPhotos(12, $offset);
        }

        foreach($photos as $photo) {
            $user = Player::getDataById($photo->user_id, array('username','look'));

            $photo->author =  $user->username;
            $photo->figure =  $user->look;

            $photo->likes = Community::getPhotosLikes($photo->id);
        }

        $this->data->photos = $photos;

        if($request == false)
            ViewService::renderTemplate('Community/photos.html', [
                'title' => LocaleService::get('core/title/community/photos'),
                'page' => 'community_photos',
                'data' => $photos
            ]);
    }
}
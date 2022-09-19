<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Config;

use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Log;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Shop as Shops;
use Cosmic\App\Library\HotelApi;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Shop
{
  
    public function remove()
    {
        $faq = Admin::removeOffer(input('post'));
        Log::addStaffLog(input('post'), 'Offer removed: ' . intval(input()->post('post')->value), request()->player->id, 'offer');
        response()->json(["status" => "success", "message" => "Offer removed successfully!"]);
    }
  
    public function editcreate()
    {
        ValidationService::validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'json'  => 'required'
        ]); 
      
        $data = [
            "title" => input('title'),
            "price" => input('price'),
            "image" => input('image'),
            "data"  => trim(input('json')),
            "description" => input('description')
        ];
      
        if (!empty(input('shopId'))) {
            $id = input('shopId');
        }

        Admin::offerEdit($data, $id ?? null);
        Log::addStaffLog($id ?? null, 'Shop item ' . isset($id) ? "modafied" : "created" . '{' . "{$data}" . '}', request()->player->id, 'shop');
        response()->json(["status" => "success", "message" => "Offer " . empty($id) ? "modafied" : "created"]);
    }
  
    public function getOfferById()
    {
       ValidationService::validate([
            'post' => 'required'
        ]);
      
        $offer = Shops::getOfferById(input('post'));
        response()->json(["data" => $offer]);
    }

    public function getOffers()
    {
        $offers = Admin::getOffers();
        Json::filter($offers, 'desc', 'id');
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/Management/shop.html', ['permission' => 'housekeeping_shop_control', 'offers' => Admin::getOffers()]);
    }
}
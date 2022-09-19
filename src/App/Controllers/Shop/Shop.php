<?php
namespace Cosmic\App\Controllers\Shop;

use Cosmic\App\Config;

use Cosmic\App\Models\Player;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Shop as Offer;

use Cosmic\App\Library\HotelApi;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

use stdClass;

class Shop
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }
  
    public function index()
    {          
        $this->data->shop = Offer::getOffers();
        $this->data->currencys = Player::getCurrencys(request()->player->id);
        $currency = Core::settings()->paypal_currency;
      
        ViewService::renderTemplate('Shop/shop.html', [
            'title' => LocaleService::get('core/title/shop/index'),
            'page'  => 'shop',
            'data'  => $this->data,
            'currency' => $currency
        ]);
    }
}
<?php
namespace Cosmic\App\Controllers\Shop;

use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class History
{
    public function index()
    {
        $history = Player::getPurchases(request()->player->id);

        ViewService::renderTemplate('Shop/history.html', [
            'title'     => LocaleService::get('core/title/shop/history'),
            'page'      => 'shop_history',
            'history'   => $history
        ]);
    }
}
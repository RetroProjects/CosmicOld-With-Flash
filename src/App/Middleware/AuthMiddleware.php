<?php
namespace Cosmic\App\Middleware;

use Cosmic\App\Controllers\Auth\Auth;
use Cosmic\App\Config;

use Cosmic\App\Middleware\BannedMiddleware;
use Cosmic\App\Models\Player;

use Cosmic\System\SessionService;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if(url()->contains('Admin')) {
            $request->setRewriteUrl(url('lost'));            
        }
      
        $request->player = Player::getDataById(SessionService::get('player_id'));
        if($request->player == null) {
           return;
        }
      
       $isBanned = new \Cosmic\App\Middleware\BannedMiddleware();
       $isBanned->handle($request);
      
       if (getIpAddress() != $request->player->ip_current || $_SERVER['HTTP_USER_AGENT'] != SessionService::get('agent')) {
            Auth::logout();
            redirect('/');
        }
    }
}


<?php
namespace Cosmic\App\Middleware;

use Cosmic\App\Controllers\Auth\Auth;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Player;

use Cosmic\System\SessionService;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AdminAuthMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if (!SessionService::exists('player_id')) {
            $request->setRewriteUrl(redirect('/'));
        }

        $request->player = Player::getDataById(SessionService::get('player_id'));

        if (!Permission::exists('housekeeping', $request->player->rank)) {
            $request->setRewriteUrl(redirect('/'));
        }
      
        if ($request->getMethod() == 'get') {
            if ($request->getUrl()->contains('/api')) {
                redirect('/housekeeping');
            }
        }
        
        if (getIpAddress() != $request->player->ip_current || $_SERVER['HTTP_USER_AGENT'] != SessionService::get('agent')) {
            Auth::logout();
            redirect('/');
        }
      
        if ($request->player === null) {
            redirect('/');
        }
    }
}

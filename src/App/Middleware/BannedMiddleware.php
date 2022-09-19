<?php
namespace Cosmic\App\Middleware;

use Cosmic\App\Controllers\Auth\Auth;
use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Ban;

use Cosmic\System\SessionService;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class BannedMiddleware implements IMiddleware
{
    public static $ban;
  
    public function handle(Request $request) : void
    {       
        $account = Ban::getBanByUserIp(getIpAddress());
        $ip_address = Ban::getBanByUserId($request->player->id);
          
        if($account || $ip_address) {
          
            self::$ban = $account ?? $ip_address;
          
            if( !empty(self::$ban)
                && !url()->contains('/help')
                && !url()->contains('/help/requests')
                && !url()->contains('/help/requests')
                && !url()->contains('/logout')) 
            {
                if(!url()->contains('assets/js/web/web.locale.js')) 
                redirect('/help/requests/new');
            }
        }
    }
}

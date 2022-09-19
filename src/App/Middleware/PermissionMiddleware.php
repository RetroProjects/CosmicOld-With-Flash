<?php
namespace Cosmic\App\Middleware;

use Cosmic\App\Models\Permission;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class PermissionMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {
        if(!Permission::exists(request()->getHeader('http_authorization'), request()->player->rank)) {
            // add friendly message + logging
            exit;
        }
      
        return;
    }
}
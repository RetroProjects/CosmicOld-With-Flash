<?php
namespace Cosmic\App\Middleware;

use Cosmic\System\LocaleService;

use Cosmic\App\Middleware\AuthMiddleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class LoggedInMiddleware implements IMiddleware
{
    public function handle(Request $request) : void
    {     
        if(!is_null($request->player)) {
            if($request->isAjax()) { 
                response()->json(["title" => "Oeps..", "dialog" => LocaleService::get('core/dialog/logged_in'), "loadpage" => "home"]);
            } else {
                redirect('/');
            }
        }
      
        return;
    }
}
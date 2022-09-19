<?php
namespace Cosmic\System;

use Cosmic\System\Handlers\ExceptionHandler;
use Cosmic\System\LocaleService;

use Cosmic\App\Middleware\AdminAuthMiddleware;
use Cosmic\App\Middleware\AuthMiddleware;
use Cosmic\App\Middleware\LoggedInMiddleware;
use Cosmic\App\Middleware\NotLoggedInMiddleware;
use Cosmic\App\Middleware\PermissionMiddleware;
use Cosmic\App\Middleware\GuildMiddleware;

use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter as Router;

class RouterService extends Router
{

    public function init()
    {            
        if(request()->getUrl()->contains('housekeeping')) {
            parent::setDefaultNamespace('\Cosmic\App\Controllers\Admin');
            
            parent::group(['prefix' => 'housekeeping', 'middleware' => AdminAuthMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {
                
                parent::get('/', 'Dashboard@view');
                parent::get('/permissions/get/commands', 'Permissions@getpermissioncommands');
                parent::get('/catalog/get/tree', 'Catalog@tree');
              
                 parent::partialGroup('/api/{param1}/{param2}', function ($param1, $param2) {
                      parent::post('/', ucfirst($param1) . '@' . $param2)->addMiddleware(PermissionMiddleware::class);
                  });

                parent::partialGroup('/{controller}/{action}', function ($controller, $action) {
                    parent::get('/view/{user}', 'Remote@user', ['defaultParameterRegex' => '[a-zA-Z0-9\d\-_=\?!@:\.,]+']);
                    parent::get('/view', ucfirst($controller) . '@' . $action);
                    parent::get('/', ucfirst($controller) . '@view');
                });
             
                parent::partialGroup('/search/get/{action}', function ($action) {
                    parent::get('/', 'Search@' . $action);
                });

                parent::get('/assets/admin/js/locale.js', function () {
                    header('Content-Type: application/javascript');
                    return 'var Locale = ' . json_encode(LocaleService::get('housekeeping/javascript', true), true) . '';
                });
              
            });
        } else {
            parent::setDefaultNamespace('\Cosmic\App\Controllers');
          
            parent::group(['middleware' => AuthMiddleware::class], function () {
          
                parent::get('/', 'Home\Index@index')->setName('index.home');
                parent::get('/home', 'Home\Index@index');
                parent::get('/lost', 'Home\Lost@index')->setName('lost');
                parent::get('/games/ranking', 'Games\Ranking@index'); 
                parent::get('/jobs', 'Jobs\Jobs@index');
                parent::get('/guilds', 'Community\Guilds\Home@index');

                parent::get('/articles', 'Community\Articles@index');
                parent::get('/article/{slug}', 'Community\Articles@index', ['defaultParameterRegex' => '[\w\-]+']);

                parent::get('/community/photos', 'Community\Photos@index');
                parent::get('/community/rares/{pagina}', 'Community\Rares@index', ['defaultParameterRegex' => '[\w\-]+']);
                parent::get('/community/rares', 'Community\Rares@index');
                parent::get('/community/staff', 'Community\Staff@index');
                parent::get('/community/team', 'Community\Staff@team');

                parent::get('/community/fansites', 'Community\Fansites@index');

                parent::get('/help', 'Help\Help@index');
                parent::get('/help/{slug}', 'Help\Help@index', ['defaultParameterRegex' => '[\w\-]+']);

                parent::get('/profile', 'Home\Proficle@profile');
                parent::get('/profile/{user}', 'Home\Profile@profile', ['defaultParameterRegex' => '[a-zA-Z0-9\d\-_=\?!@:\.,]+']);
                parent::post('/profile/search', 'Home\Profile@search');

                parent::get('/shop', 'Shop\Shop@index');
                parent::get('/shop/club', 'Shop\Club@index');
                parent::get('/shop/drawbadge', 'Shop\Drawbadge@index');
                parent::get('/shop/order/view/{orderId}', 'Shop\History@order');

                parent::get('/guilds/{slug}', 'Community\Guilds\Category@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);
                parent::get('/guilds/{slug}/page/{page}', 'Community\Guilds\Category@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);
                parent::get('/guilds/{group}/thread/{slug}', 'Community\Guilds\Topic@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);
                parent::get('/guilds/{group}/thread/{slug}/page/{page}', 'Community\Guilds\Topic@index', ['defaultParameterRegex' => '[\w\-]+'])->addMiddleware(GuildMiddleware::class);

                parent::get('/jobs/my', 'Jobs\Jobs@my');
                parent::get('/jobs/{id}/apply', 'Jobs\Apply@index');

                parent::get('/api/player/count', 'Client\Client@count');

                parent::get('/assets/js/web/web.locale.js', function () {
                    header('Content-Type: application/javascript');
                    return 'var Locale = ' . json_encode(LocaleService::get('website/javascript', true), true) . '';
                });

                parent::group(['middleware' => LoggedInMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {
                    parent::get('/registration/{name?}', 'Auth\Registration@index');
                    parent::get('/facebook', 'Home\Login@facebook');

                    parent::get('/password/claim', 'Password\Claim@index');
                    parent::get('/password/reset/{token}', 'Password\Reset@index');
                });

                parent::partialGroup('/api/{callback}', function ($callback) {
                    parent::all('/{param}', 'Api@' . $callback);
                    parent::all('/', 'Api@' . $callback);
                });
              
                parent::group(['middleware' => NotLoggedInMiddleware::class, 'exceptionHandler' => ExceptionHandler::class], function () {

                    parent::get('/disconnect', 'Home\Lost@index')->setName('index.home');

                    parent::get('/hotel', 'Client\Client@hotel');
                    parent::get('/client', 'Client\Client@client');

                    parent::get('/logout', 'Auth\Login@logout');
                    parent::get('/settings', 'Settings\Preferences@index');
                    parent::get('/settings/email', 'Settings\Email@index');
                    parent::get('/settings/password', 'Settings\Password@index');
                    parent::get('/settings/namechange', 'Settings\Namechange@index');
                    parent::get('/settings/preferences', 'Settings\Preferences@index');
                    parent::get('/settings/verification', 'Settings\Verification@index');

                    parent::get('/help/requests/view', 'Help\Requests@index');
                    parent::get('/help/requests/new', 'Help\Ticket@index');
                    parent::get('/help/requests/{ticket}/view', 'Help\Requests@ticket', ['defaultParameterRegex' => '[0-9]+']);

                    parent::partialGroup('/guilds/post/{controller}/{action}', function ($controller, $action) {
                        parent::post('/', 'Community\Guilds\\' . ucfirst($controller) . '@' . $action)->addMiddleware(GuildMiddleware::class);
                    });

                });
                parent::partialGroup('{directory}/{controller}/{action}', function ($directory, $controller, $action) {
                     if(request()->getMethod() == "post"){
                        parent::post('/', ucfirst($directory) . '\\' . ucfirst($controller) . '@' . $action);
                     }
                });
              
            });
        }

        parent::start();
    }
}

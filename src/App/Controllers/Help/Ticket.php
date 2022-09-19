<?php
namespace Cosmic\App\Controllers\Help;

use Cosmic\App\Middleware\BannedMiddleware;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class Ticket
{
    public function index()
    {
        $banned_reason = BannedMiddleware::$ban;

        ViewService::renderTemplate('Help/new.html', [
            'title'     => LocaleService::get('core/title/help/new'),
            'page'      => 'help_new',
            'banned_reason' => $banned_reason
        ]);
    }
}
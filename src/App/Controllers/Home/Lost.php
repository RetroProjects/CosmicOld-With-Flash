<?php
namespace Cosmic\App\Controllers\Home;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class Lost
{
    private $data;

    public function index()
    {
        ViewService::renderTemplate('Home/lost.html', [
            'title' => LocaleService::get('core/title/lost'),
            'page'  => 'lost',
            'data'  => $this->data
        ]);
        exit;
    }
}
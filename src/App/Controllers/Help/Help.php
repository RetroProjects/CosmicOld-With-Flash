<?php
namespace Cosmic\App\Controllers\Help;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

use stdClass;

class Help
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function helpBySlug($slug)
    {
        $slug_id = explode('-', $slug);
        $article = \Cosmic\App\Models\Help::getById($slug_id[0]);
        if ($article == null) {
            (redirect('/help'));
        }

        $this->data->help = $article;
    }

    public function helpAction()
    {
        $category = \Cosmic\App\Models\Help::getCategories();
        if($category == null) {
            redirect('/');
        }

        foreach ($category as $row) {
            $row->faq = \Cosmic\App\Models\Help::getByCategory($row->id);
        }
        
        $this->data->categories = $category;
    }

    public function index($slug = null)
    {
        if($slug == null) {
            $this->helpAction();
        } else {
            $this->helpBySlug($slug);
        }

        ViewService::renderTemplate('Help/help.html', [
            'title'     => LocaleService::get('core/title/help/index'),
            'page'      => 'help',
            'data'      => $this->data
        ]);
    }
}

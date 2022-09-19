<?php
namespace Cosmic\App\Controllers\Community;

use Cosmic\App\Models\Player;
use Cosmic\App\Models\Community;

use Cosmic\System\ViewService;

class Rares
{
	public function search()
    {
		if(isset(input()->post('word')->value)){
			$pages = Community::getRareValuePagesByString(input()->post('word')->value);
		response()->json(["status" => "success", "pages" => $pages]);
		
		}
	}
    public function index($pagina = null)
    {
		$route = explode('-', $pagina ?? 0 . '-' . $pagina);
if($route[0] == 0){
	$rares = Community::getRareLastList();
}else{
	$rares = Community::getRareList($route[0]);
		$pagename = Community::getPageNameFromId($route[0]);
}
			$pages = Community::getPageRares();
		
        ViewService::renderTemplate('Community/rares.html', [
            'title' => isset($pagename) ? $pagename->name : null,
            'page'  => 'community_rares',
            'pages'  => isset($pages) ? $pages : null,
			'rares' => isset($rares) ? $rares : null
        ]);
    }
}
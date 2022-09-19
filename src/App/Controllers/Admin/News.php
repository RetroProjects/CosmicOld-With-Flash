<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Config;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Log;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Player;
use Cosmic\App\Library\Upload;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

use stdClass;

class News
{
    private $data;
    private $file;
    public $uploadPath;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function getnews()
    {
        $news = Admin::getNews();

        if (empty($news)) {
            response()->json(["status" => "error", "message" => "We were unable to find any news items"]);
        }

        foreach ($news as $row) {
            $row->author = Player::getDataById($row->author, 'username')->username ?? 'Management';
            $row->timestamp = date('d-M-Y H:i:s', $row->timestamp);
        }

        Json::filter($news, 'desc', 'id');
    }

    public function getcategorys()
    {
        $category = Admin::getNewsCategories();

        if (empty($category)) {
            response()->json(["status" => "error", "message" => "We were unable to find any news categories"]);
        }

        Json::filter($category, 'desc', 'id');
    }

    public function add()
    {
        ValidationService::validate([
            'title'         => 'required|max:50',
            'short_story'   => 'required|max:200',
            'full_story'    => 'required'
        ]);

        $id = input()->post('newsId')->value ?? 0;

        $title = input()->post('title')->value;
        $short_story = input()->post('short_story')->value;
        $full_story = input()->post('full_story')->value;
        $category = input()->post('category')->value;
        $images = input()->post('images')->value;
        $imagePath = input()->file('imagesUpload');

        if (!empty($imagePath)) {
            if ($this->imageUpload($imagePath)) {
                $imagePath = '/uploads/' . $this->uploadPath;
            }
        } else {
            if ($id != 0) {
                $imagePath = Admin::getNewsById($id)->header;
            }
        }
      
        if ($id == 0) {
            Admin::addNews($title, $short_story, $full_story, $category, $imagePath, $images, request()->player->id);
            Log::addStaffLog('-1', 'News placed: ' . $title, request()->player->id, 'news');
          
            response()->json(["status" => "success", "message" => "News article is posted!"]);
        }

        Admin::editNews($id, $title, $short_story, $full_story, $category, $imagePath, $images, request()->player->id);
        Log::addStaffLog('-1', 'News edit: ' . $title, request()->player->id, 'news');
      
        response()->json(["status" => "success", "message" => "News edit successfully"]);
    }

    public function edit()
    {
        if (empty(input()->post('post')->value)) {
            response()->json(["status" => "error", "message" => "We were unable to find this news item"]);
        }

        $this->data->news = Admin::getNewsById(input()->post('post')->value);
        $this->data->category = Admin::getNewsCategories();
        echo Json::encode($this->data);
    }

    public function remove()
    {
        $news = Admin::removeNews(input()->post('post')->value);

        if (empty($news)) {
            response()->json(["status" => "error", "message" => "We were unable to find this news item"]);
        }

        Log::addStaffLog('-1', 'News removed: ' . input()->post('post')->value, request()->player->id, 'news');  
        response()->json(["status" => "success", "message" => "News removed succesfully!"]);
    }

    public function addcategory()
    {
        ValidationService::validate([
            'post'          => 'required|max:50'
        ]);

        Admin::addNewsCategory(input()->post('post')->value);
        Log::addStaffLog('-1', 'News category added: ' . input()->post('post')->value, request()->player->id, 'news');
      
        response()->json(["status" => "success", "message" => "Category successfully added!"]);
    }

    public function editcategory()
    {
        $category = Admin::getNewsCategoryById(input()->post('post')->value);

        if (empty($category)) {
            response()->json(["status" => "error", "message" => "Category does not exists!"]);
        }

        Log::addStaffLog('-1', 'News category edit: ' . $category->category . ' to ' .input()->post('post')->value, request()->player->id, 'news');
        Admin::editNewsCategory($category->id, input()->post('value')->value);
      
        response()->json(["status" => "success", "message" => "Category edit is successfully!"]);
    }

    public function removecategory()
    {
        $category = Admin::getNewsCategoryById(input()->post('post')->value);
        if (empty($category)) { 
            response()->json(["status" => "error", "message" => "Category does not exists!"]);
        }

        Log::addStaffLog('-1', 'News category removed: ' . $category->category, request()->player->id, 'news');
        Admin::removeNewsCategory($category->id);
      
        response()->json(["status" => "success", "message" => "Category removed succesfully!"]);
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/Management/news.html', [
            'permission' => 'housekeeping_website_news'
        ]);
    }

    protected function imageUpload($imagePath)
    {
        if(preg_match("/^[^\?]+\.(jpg|jpeg|gif|png)(?:\?|$)/", $imagePath->filename, $matchings)) {
          
            $upload = new Upload();
          
            $upload->upload_dir = Core::settings()->upload_path;
            $upload->extensions = ['.jpg', '.jpeg', '.gif', '.png'];
            $upload->the_temp_file = $imagePath->tmpName;
            $upload->the_file = sprintf(uniqid() . ".%s", $matchings[1]);
            $this->uploadPath = $upload->the_file;
          
            if ($upload->upload()) {
                return true;
            }
            return false;
      	}
    }
}

<?php
namespace Cosmic\App\Controllers\Jobs;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Community;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Apply
{
    public function index($id)
    {
        $job = Community::getJob($id);
        if(empty($job)) {
            redirect('/');
        }
      
        ViewService::renderTemplate('Jobs/apply.html', [
            'title' => LocaleService::get('core/title/jobs/apply'),
            'page'  => 'apply',
            'job'   => $job
        ]);
    }

    public function request()
    {
        ValidationService::validate([
            'age'               =>   'required|numeric',
            'job_why'           =>   'required',
            'when_monday'       =>   'required',
            'when_tuesday'      =>   'required',
            'when_thursday'     =>   'required',
            'when_wednesday'    =>   'required',
            'when_friday'       =>   'required',
            'when_sunday'       =>   'required',
            'when_saturday'     =>   'required'
        ]);

        $player_id              =   request()->player->id;
        $job_id                 =   input('job_id');
        $firstname              =   input('name');
        $message                =   input('job_why');
        $available_monday       =   input('when_monday');
        $available_tuesday      =   input('when_tuesday');
        $available_wednesday    =   input('when_wednesday');
        $available_thursday     =   input('when_thursday');
        $available_friday       =   input('when_friday');
        $available_saturday     =   input('when_saturday');
        $available_sunday       =   input('when_sunday');
        
      
        $job = Community::getJob($job_id);
        if(empty($job)) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
        
        Community::addJobApply($job_id, $player_id, Helper::filterString($firstname), Helper::filterString($message), $available_monday, $available_tuesday, $available_wednesday, $available_thursday, $available_friday,$available_saturday, $available_sunday);
        response()->json(["status" => "success", "message" => LocaleService::get('website/apply/content_1'), "replacepage" => "jobs/my"]);    
    }
}
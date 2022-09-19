<?php
namespace Cosmic\App\Controllers\Jobs;

use Cosmic\App\Models\Community;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class Jobs
{
    public function my()
    {
        $jobs = Community::getMyJobApplication(request()->player->id);
      
        ViewService::renderTemplate('Jobs/my.html', [
            'title' => LocaleService::get('core/title/jobs/index'),
            'page'  => 'jobs',
            'jobs'  => $jobs
        ]);
    }
  
    public function index()
    {
        $jobs = Community::getJobs();
      
        if(request()->player) {
            foreach($jobs as $job) {
                if(Community::getJobApplication($job->id, request()->player->id)) {
                    $job->apply = true;
                }
            }
        }
        
        ViewService::renderTemplate('Jobs/jobs.html', [
            'title' => LocaleService::get('core/title/jobs/index'),
            'page'  => 'jobs',
            'jobs'  => $jobs
        ]);
    }
}
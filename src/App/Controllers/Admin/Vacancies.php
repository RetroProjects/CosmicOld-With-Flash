<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Helpers\Helper;
use Cosmic\App\Models\Admin;
use Cosmic\App\Models\Community;
use Cosmic\App\Models\Player;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;

class Vacancies
{
    public function delete() 
    {
        ValidationService::validate([
            'id'  => 'required|numeric'
        ]);

        $jobid = input()->post('id')->value;
        
        $job = Community::getJob($jobid);
      
        if(!empty($job)) {
            Admin::deleteJob($jobid);
            response()->json(["status" => "success", "message" => "Vacancies is has been deleted!"]);
        }
    }
  
    public function editadd() 
    {
        ValidationService::validate([
            'job_title'           => 'required',
            'small_description'   => 'required|max:200',
            'full_description'    => 'required'
        ]);

        $jobid = input()->post('jobid')->value;
        $job_title = input()->post('job_title')->value;
        $small_description = input()->post('small_description')->value;
        $full_description = input()->post('full_description')->value;
      
        $job = Community::getJob($jobid);
      
        if(!empty($job)) {
            Admin::editJob($jobid, $job_title, $small_description, $full_description);
            response()->json(["status" => "success", "message" => "Job are edited!"]);
        }
      
        Admin::addJob($job_title, $small_description, $full_description);
        response()->json(["status" => "success", "message" => "Job has been added!"]);
    }
  
    public function accept()
    {
        ValidationService::validate([
            'id'  => 'required|numeric'
        ]);
      
        $jobid = input()->post('id')->value;
        
        $job = Community::getApplicationById($jobid);
      
        if(!empty($job)) {
            Admin::changeJobStatus($jobid);
            response()->json(["status" => "success", "message" => "Job changed to closed!"]);
        }
    }
  
    public function seejob()
    {
        $this->job = new \stdClass();
        $this->job->job = Community::getApplicationById(input()->post('id')->value);
        $this->job->job->message = Helper::filterString($this->job->job->message);
        Json::encode($this->job);
    } 
  
    public function getjob() 
    {
        $jobs = Community::getJob(input()->post('id')->value);
        Json::encode($jobs);
    }
  
    public function getApplications() 
    {
        $applications = Community::getAllApplications(input()->post('jobid')->value);
        foreach($applications as $application) {
            $application->user_id = Player::getDataById($application->user_id, 'username')->username;
            $application->message = Helper::filterString($application->message);
        }
      
        Json::filter($applications, 'desc', 'id');
    }
  
    public function getVacanies() 
    {
        $jobs = Community::getJobs();
        foreach($jobs as $job) {
            $job->applys = count(Community::getJobApplications($job));
        }
      
        Json::filter($jobs, 'desc', 'id');
    }
  
    public function view()
    {
        ViewService::renderTemplate('Admin/Management/vacancies.html', ['permission' => 'housekeeping_permissions']);
    }
}

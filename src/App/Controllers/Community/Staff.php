<?php
namespace Cosmic\App\Controllers\Community;

use Cosmic\App\Models\Permission;
use Cosmic\App\Models\Player;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;

class Staff
{
    public function index()
    {
        $ranks = Permission::getRanks();

        foreach ($ranks as $row) {
          
            if(!Permission::exists('website_invisible_staff', $row->id)) {
                $row->users = Player::getDataByRank($row->id);

                if (!empty($row->users) && is_array($row->users)) {
                    foreach ($row->users as $users) {
                        $users->settings = Player::getSettings($users->id);
                    }
                }
            }
        }

        ViewService::renderTemplate('Community/staff.html', [
            'title' => LocaleService::get('core/title/community/staff'),
            'page'  => 'community_staff',
            'action' => 'staff',
            'data'  => $ranks
        ]);
    }
  
    public function team()
    {
        $ranks = Permission::getTeams();
      
        foreach($ranks as $row) {
            $row->users = Player::getByExtraRank($row->id);
        }
      
        ViewService::renderTemplate('Community/staff.html', [
            'title' => LocaleService::get('core/title/community/staff'),
            'page'  => 'community_staff',
            'action' => 'team',
            'data'  => $ranks
        ]);
    }
}
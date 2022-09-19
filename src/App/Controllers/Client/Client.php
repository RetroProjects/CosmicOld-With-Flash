<?php
namespace Cosmic\App\Controllers\Client;

use Cosmic\App\Config;

use Cosmic\App\Models\Api;
use Cosmic\App\Models\Ban;
use Cosmic\App\Models\Core;
use Cosmic\App\Models\Player;
use Cosmic\App\Models\Room;

use Cosmic\System\LocaleService;
use Cosmic\System\ViewService;
use Cosmic\System\TokenService;

use Cosmic\App\Library\HotelApi;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use stdClass;

class Client
{
    private $data;
    private $record;

    public function client()
    {
        $this->data = new stdClass();
    
        $reader = new Reader(__DIR__. '/../../' .Config::vpnLocation);

        try {
            $this->record = $reader->asn(getIpAddress());
        } catch (AddressNotFoundException $e) {
        } catch (InvalidDatabaseException $e) {
        }

        // Check if an ASN model record has been found
        if ($this->record) {

            // Get banned ASN models
            $asn = Ban::getNetworkBanByAsn($this->record->autonomousSystemNumber);

            // Render vpn view if ASN has been disallowed
            if ($asn) {
                ViewService::renderTemplate('Client/vpn.html', ['asn' => $asn->asn, 'type' => 'vpn']);
                exit;
            }
        }


        $OS = substr($_SERVER['HTTP_USER_AGENT'], -2);

        // Check whether request is made using Puffin browser.
        $isPuffin = !empty(strpos($_SERVER['HTTP_USER_AGENT'], "Puffin"));

        if ($isPuffin && ($OS == "WD" || $OS == "LD" || $OS == "MD")) {
            ViewService::renderTemplate('Client/vpn.html', ['type' => 'puffin']);
            exit;
        }

        $user = Player::getDataById(request()->player->id);
      
        $this->data->auth_ticket = TokenService::authTicket($user->id);
        $this->data->unique_id = sha1($user->id . '-' . time());

        Player::update($user->id, ["auth_ticket" => $this->data->auth_ticket]);
      
        if ($user->getMembership()) {
            HotelApi::execute('setrank', ['user_id' => $user->id, 'rank' => $user->getMembership()->old_rank]);
            $user->deleteMembership();
        }

        ViewService::renderTemplate('Client/client.html', [
            'title' => LocaleService::get('core/title/hotel'),
            'room' => explode("=", url()->getOriginalUrl())[1] ?? null,
            'data'  => $this->data,
            'client' => Config::client,
            'site' => Config::site
        ]);
    }

    public function hotel()
    {
        ViewService::renderTemplate('base.html', [
            'title' => LocaleService::get('core/title/hotel'),
            'page'  => 'home'
        ]);
    }

    public function count()
    {
        echo Core::getOnlineCount();
        exit;
    }
}

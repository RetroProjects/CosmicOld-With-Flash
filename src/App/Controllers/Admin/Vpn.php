<?php
namespace Cosmic\App\Controllers\Admin;

use Cosmic\App\Config;
use Cosmic\App\Models\Ban;
use Cosmic\App\Models\Player;
use Cosmic\App\Library\Json;

use Cosmic\System\ViewService;
use Cosmic\System\ValidationService;
use Cosmic\System\LocaleService;
use stdClass;

use MaxMind\Db\Reader\InvalidDatabaseException;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
class Vpn
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function ban()
    {
        $player = input()->post('id')->value;
        $reader = new Reader(__DIR__. '/../../' . Config::vpnLocation);

        try {
            $last_ip = Player::getDataByUsername($player, 'ip_current')->ip_current;

            $record = $reader->asn($last_ip);

            $asn = Ban::getNetworkBanByAsn($record->autonomousSystemNumber);
            if ($asn != null) {
                response()->json(["status" => "success", "message" => "AS {$asn->asn} is already banned"]);
            }

            Ban::createNetworkBan($record->autonomousSystemNumber, $record->autonomousSystemOrganization, request()->player->id);
            response()->json(["status" => "success", "message" => "AS {$record->autonomousSystemNumber} is added to our ban list"]);

        } catch (AddressNotFoundException $e) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        } catch (InvalidDatabaseException $e) {
            response()->json(["status" => "error", "message" => LocaleService::get('core/notification/something_wrong')]);
        }
    }

    public function delete()
    {
        $ban = Ban::getNetworkBanById(input()->post('asn')->value);
        if ($ban == null) {
            response()->json(["status" => "error", "message" => "AS {$ban->asn} is not banned"]);
        }

        Ban::removeNetworkBan($ban->asn);
        response()->json(["status" => "success", "message" => "AS {$ban->asn}/{$ban->host} is deleted"]);
    }

    public function getasnbans()
    {
        $asn = Ban::getNetworkBans();
        if ($asn) {
            foreach ($asn as $row) {
                $row->added_by = Player::getDataById($row->added_by, 'username')->username;
            }
        }

        Json::filter($asn, 'desc', 'id');
    }

    public function view()
    {
        ViewService::renderTemplate('Admin/Management/vpn.html', ['permission' => 'housekeeping_vpn_control']);
    }
}

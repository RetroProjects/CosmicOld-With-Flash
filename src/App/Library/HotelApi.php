<?php
namespace Cosmic\App\Library;

use Cosmic\App\Models\Core;
use Cosmic\System\LocaleService;
use Origin\Socket\Socket;

class HotelApi {
  
    public $result = [];
  
    public $socket;
    public $settings;
  
    public $serverPort;
    public $serverHost;
    public $timeout;
    public $protocol;
  
    public function __construct()
    {
        $this->settings = Core::settings();
      
        $rcon_api_persistent = filter_var(Core::settings()->rcon_api_persistent, FILTER_VALIDATE_BOOLEAN); 
      
        $this->socket = new Socket([
            'host' => (string)$this->settings->rcon_api_host,
            'protocol' => (string)$this->settings->rcon_api_protocol,
            'port' => (int)$this->settings->rcon_api_port,
            'timeout' => (int)$this->settings->rcon_api_timeout,
            'persistent' => $rcon_api_persistent,
        ]);
    }
  
   public static function flatten($array, $prefix = '')
    {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + self::flatten($value);
            }
            else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

  
    public function send($command)
    {
        try {
            if ($this->socket->connect()) {
                $this->socket->write($command);
                return json_decode($this->socket->read());
            }
        } catch (\Exception $e) {  
            response()->json(["status" => "error", "message" => LocaleService::get('rcon/exception')]);
        }
    
        $this->socket->disconnect();
    }
  
    public static function execute($param, $data = null, $merge = false)
    {
        $command = json_encode(['key' => $param, 'data' => ($merge == true) ? self::flatten($data) : $data]);
        return (new self)->send($command);
    }
}
  

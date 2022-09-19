<?php
namespace Cosmic\App\Library;

use Cosmic\App\Config;
use Cosmic\App\Models\Core;

use PayPalCheckoutSdk\Core\PayPalHttpClient;

class Paypal
{
    public static $environment;
    
    public static function client()
    {
        $boolean = filter_var(Core::settings()->paypal_sandbox_enabled, FILTER_VALIDATE_BOOLEAN); 
        self::$environment = ($boolean) ? 'SandboxEnvironment' : 'ProductionEnvironment';
        return new PayPalHttpClient(self::production());
    }

    public static function production()
    {
        $class = '\PayPalCheckoutSdk\Core\\' . self::$environment;
        return new $class(Core::settings()->paypal_client_id, Core::settings()->paypal_secret_id);
    }
  
}
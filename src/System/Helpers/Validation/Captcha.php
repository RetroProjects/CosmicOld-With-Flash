<?php
namespace Cosmic\System\Helpers\Validation;

use Cosmic\App\Config;
use Cosmic\App\Models\Core;

use Cosmic\System\LocaleService;

use Rakit\Validation\Rule;


class Captcha extends Rule
{
    /** @var string */
    protected $message;

    /**
     * Check $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct()
    {
        $this->message = LocaleService::get('core/pattern/captcha');
    }

    public function check($value): bool
    {
        return $this->captcha($value);
    }

    public function captcha($value) {
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = ['secret'   => Core::settings()->recaptcha_secretkey,
                 'response' => $value,
                 'remoteip' => getIpAddress()];
                 
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data) 
            ]
        ];
    
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return json_decode($result)->success;
    }
}

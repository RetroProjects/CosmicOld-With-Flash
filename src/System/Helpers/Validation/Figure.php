<?php
namespace Cosmic\System\Helpers\Validation;

use Cosmic\App\Config;
use Cosmic\System\LocaleService;

use Rakit\Validation\Rule;

class Figure extends Rule
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
        $this->message = LocaleService::get('core/notification/something_wrong');
    }

    public function check($value): bool
    {
        return $this->figure($value);
    }

    public function figure($value) {
        if(in_array(substr($value, strrpos($value, 'hr-')), Config::look['male']) ? substr($value, strrpos($value, 'hr-')) : Config::look['female']) {
            return true;
        }

        return false;
    }
}

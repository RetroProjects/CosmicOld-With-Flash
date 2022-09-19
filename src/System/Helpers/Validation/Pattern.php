<?php
namespace Cosmic\System\Helpers\Validation;

use Cosmic\System\LocaleService;

use Rakit\Validation\Rule;

class Pattern extends Rule
{
    /** @var string */
    protected $message;

    protected $fillableParams = ['pattern'];

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */

    public function __construct() {
        $this->message = ':attribute ' . LocaleService::get('core/pattern/invalid');
    }

    public function check($value): bool
    {
        $this->requireParameters($this->fillableParams);
        $regex = '/^(' . str_replace('OR', '|', $this->parameter('pattern')) . ')$/u';
        return preg_match($regex, $value);
    }
}

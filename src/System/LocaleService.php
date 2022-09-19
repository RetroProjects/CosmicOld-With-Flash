<?php
namespace Cosmic\System;

use Cosmic\App\Config;

class LocaleService
{
    /**
     * Get the language value
     *
     * @param $path
     * @param bool $all
     * @return bool|mixed
     */
    public static function get($path = null, $all = false)
    {
        require_once __DIR__.'/../App/Locale/'.Config::language.'.php';

        if ($path) {
            $locale = $GLOBALS['language'];
            $path = explode('/', $path);

            if($all) {
                if(isset($locale[$path[0]][$path[1]]))
                    return $locale[$path[0]][$path[1]];
            }

            foreach ($path as $bit) {
                if (isset($locale[$bit])) {
                    $locale = $locale[$bit];
                }
            }

            return $locale;
        }

        return false;
    }
}
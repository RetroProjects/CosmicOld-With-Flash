<?php
namespace Cosmic\System;

/**
 * Session handler.
 */

class SessionService
{
    /**
     * Create session
     *
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function set($data)
    {
        if(!is_array($data)) {
            return $_SESSION[$data] = $data;
        }

        foreach($data as $row => $value) {
            $_SESSION[$row] = $value;
        }

        return;
    }

    /**
     * Get session
     *
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    /**
     * Check session
     *
     * @param $name
     * @return bool
     */
    public static function exists($name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Delete session
     *
     * @param $name
     */
    public static function delete($name)
    {
        unset($_SESSION[$name]);
    }

    public static function destroy()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}

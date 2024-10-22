<?php

class MySession {
    private static $singleton;

    private function __construct()
    {
        session_start();
    }

    public static function getSingleton() {
        if (!isset(self::$singleton)) {
            self::$singleton = new MySession();
        }
        return self::$singleton;
    }

    function setSession(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    function isSession(string $key)
    {
        return isset($_SESSION[$key]);
    }

    function getSession(string $key)
    {
        if ($this->isSession($key)) {
            return $_SESSION[$key];
        }
        return null;
    }

    function removeSession(string $key)
    {
        unset($_SESSION[$key]);
    }
}
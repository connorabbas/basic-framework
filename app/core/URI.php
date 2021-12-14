<?php
class URI
{
    public static function get($param)
    {
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            return explode('&', explode($param . '=', $_SERVER['REQUEST_URI'])[1])[0];
        } else {
            return false;
        }
    }
}

<?php
class Request
{
    public static function post($data)
    {
        $file = file_get_contents("php://input");
        $file = explode("&", $file);
        for ($i = 0; $i < count($file); $i++) {
            $sub = explode('=', $file[$i]);
            if ($sub[0] == $data) {
                return utf8_decode(urldecode($sub[1]));
            }
        }
    }
}

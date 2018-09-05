<?php

namespace Akane\Core;

class Http
{
	public static function redirect($url='')
	{
		header("Location: ".$url);
		exit();
	}

	public static function uri_segment($s = 0)
	{
        $suri = $_SERVER['REQUEST_URI'];
        $parse = parse_url($suri);
        $uri = ltrim($parse['path'],'/');

        if (!empty($uri))
        {
            $segment = explode('/',$uri);
            if (count($segment) > 0){
                if (isset($segment[$s])){
                    $segment[$s] = filter_var($segment[$s], FILTER_SANITIZE_STRING);
                    return $segment[$s];
                }
            }
        }
    }

    public static function getIPAddress()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
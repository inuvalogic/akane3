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
        $uri = $_SERVER['REQUEST_URI'];
        $parse = parse_url($suri);
        $uri = trim($parse['path'],'/');
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
}
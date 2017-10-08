<?php

namespace Akane\Core;

class Router
{
	public static function loadIndex(Container $container)
	{
		$appclassname = '\\Akaneapp\\Controller\\HomeController';
		if (class_exists($appclassname)){
			$class = new $appclassname($container);
        	$class->indexAction();
		} else {
        	$class = new \Akane\Controller\HomeController($container);
        	$class->indexAction();
    	}
	}

	public static function loadNotFound(Container $container)
	{
		$class = new \Akane\Controller\PageController($container);
        $class->notfoundAction();
	}

	public static function parse(Container $container)
	{
		$routes = [];
		
		$routes_file = APP_CONFIG_DIR.'routes.php';
		if (file_exists($routes_file)){
			include $routes_file;
		}

        $notfound = false;
        
		if (isset($routes) && is_array($routes))
		{
	        $suri = $_SERVER['REQUEST_URI'];
	        $p = parse_url($suri);
	        $uri = trim($p['path'], '/');

	        if (!empty($uri))
	        {
				foreach ($routes as $from => $to)
	            {
					if (preg_match('#^'.$from.'$#', $uri))
	                {
	                    $parse = explode(':', $to);

	                    if ( count($parse) > 0)
                        {
		                    if (isset($parse[1])){
			                    $act = $parse[1];
			                } else {
			                    $act = 'index';
			                }
			                $method = $act.'Action';
			                $appclassname = '\Akaneapp\Controller\\'.ucwords($parse[0]).'Controller';
			                $classname = '\Akane\Controller\\'.ucwords($parse[0]).'Controller';
			                if (class_exists($appclassname) && method_exists($appclassname, $method)){
			                    $class = new $appclassname($container);
			                    $class->{$method}();
			                } else if (class_exists($classname) && method_exists($classname, $method)){
			                    $class = new $classname($container);
			                    $class->{$method}();
			                } else {
			                    $notfound = true;
			                }
						} else {
							$notfound = true;
						}
	                }
	            }

	            if ($notfound==true){
	            	self::loadNotFound($container);
	            }

            } else {
            	self::loadIndex($container);
            }
		}
	}
}
<?php

namespace Akane\Core;

class Router
{
	public $routes = array();

	public function loadIndex(Container $container)
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

	public function loadNotFound(Container $container)
	{
		$class = new \Akane\Controller\PageController($container);
        $class->notfoundAction();
	}

	public function setRoutes($new_routes)
	{
		$this->routes = array_merge($this->routes, $new_routes);
	}

	public function loadRoutes($routes)
	{
		if (is_array($routes)){
			$this->setRoutes($routes);		
		}
	}

	public function parse(Container $container)
	{
        $notfound = false;
        
		if (is_array($this->routes))
		{
	        $suri = $_SERVER['REQUEST_URI'];
	        $p = parse_url($suri);
	        $uri = ltrim($p['path'], '/');
        	$uri = filter_var($uri, FILTER_SANITIZE_STRING);

	        if (!empty($uri))
	        {
				foreach ($this->routes as $from => $to)
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
	            	$this->loadNotFound($container);
	            }

            } else {
            	$this->loadIndex($container);
            }
		}
	}
}
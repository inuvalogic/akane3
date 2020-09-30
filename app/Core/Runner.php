<?php

namespace Akane\Core;

use Akane\Core\Container;
use Akane\Core\Http;
use Akane\Core\Session;
use Akane\Core\Database;
use Akane\Core\Template;

class Runner
{
    public $classes;

	public function boot()
    {
        include __DIR__.'/../common.php';
        
        $session = new Session;
        $session->open();

        $db = new Database;
        $db->init();
        
        $container = new Container;
        $container->register('session', $session);
        $container->register('db', $db);
        
        $this->initClass($classes);

        self::loadClass($container, $this->classes);

        if (!isset($routes)){
            $routes = array();
        }

        $router = new Router;
        $router->loadRoutes($routes);
        $router->parse($container);
    }

    public function initClass($classes)
    {
        $this->classes = $classes;
    }

    public function loadClass(Container $container, array $namespaces)
    {
        foreach ($namespaces as $namespace => $classes) {
            foreach ($classes as $name) {
                $class = '\\Akane\\'.$namespace.'\\'.$name;
                $container->register(lcfirst($name), new $class($container));
            }
        }

        return $container;
    }
}
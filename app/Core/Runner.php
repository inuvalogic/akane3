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
        $session = new Session;
        $session->open();

        $db = new Database;
        $db->init();
        
        $container = new Container;
        $container->register('session', $session);
        $container->register('db', $db);

        self::loadClass($container, $this->classes);

        $suri = $_SERVER['REQUEST_URI'];
        $p = parse_url($suri);
        $uri = trim($p['path'], '/');

        if (!empty($uri)){
            $routes = explode('/', $uri);
            $op = '';
            if (count($routes)>0){
                $op = $routes[0];
                if (isset($routes[1])){
                    $act = $routes[1];
                } else {
                    $act = 'index';
                }
                $method = $act.'Action';
                $classname = '\Akane\Controller\\'.ucwords($op).'Controller';
                if (class_exists($classname) && method_exists($classname, $method)){
                    $class = new $classname($container);
                    $class->{$method}();
                } else {
                    $class = new \Akane\Controller\PageController($container);
                    $class->notfoundAction();
                }
            }
        } else {
            $class = new \Akane\Controller\HomeController($container);
            $class->indexAction();
        }
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
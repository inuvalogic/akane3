<?php

if (!defined('__SITEURL__')){
	define('__SITEURL__', 'http://'.$_SERVER['HTTP_HOST']);
}

if (!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('CONFIG_DIR')){
	define('CONFIG_DIR' , __DIR__ . '..' . DS . '..' . DS . 'Config' . DS);
}

if (!defined('APP_CONFIG_DIR')){
	define('APP_CONFIG_DIR' , 'app' . DS . 'Config' . DS);
}
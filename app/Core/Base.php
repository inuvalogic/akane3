<?php

namespace Akane\Core;

use Akane\Core\Container;

class Base
{
	const SETTING_TABLE = 'setting';
	private $container;

	public function getContainer()
	{
		return $this->container;
	}

	public function __construct(Container $container)
	{
		foreach ($container->getAll() as $key => $value) {
			$this->{$key} = $value;
		}
		$this->container = $container;
	}
	
	public function __get($name)
	{
		// $arr = preg_split('/(?=[A-Z])/',$str);

		$modelClass = '\Akane\\Model\\'.ucwords($name);

		if (class_exists($modelClass))
		{
			$container = $this->getContainer();
			return new $modelClass($container);
		} else {
			throw new \Exception("Invalid Model Name <b>" . $name . "</b>");
		}
	}
}
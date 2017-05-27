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
		$arr = preg_split('/(?=[A-Z])/',$name);

		if (isset($arr[1])){
			$fqnClass = '\Akane\\' . $arr[1] . '\\'.ucfirst($name);
		} else {
			$fqnClass = '\Akane\\Model\\'.ucfirst($name);
		}

		if (class_exists($fqnClass))
		{
			$container = $this->getContainer();
			return new $fqnClass($container);
		} else {
			throw new \Exception("Invalid Class Name <b>" . $name . "</b>");
		}
	}
}
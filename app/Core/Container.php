<?php

namespace Akane\Core;

use Akane\Core\Container;

class Container
{
	private $container;
	
	public function getAll()
	{
		return $this->container;
	}

	public function register($name, $object)
	{
		$this->container[$name] = $object;
	}
}
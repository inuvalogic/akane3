<?php

namespace Akane\Core;

use Akane\Core\Container;

class Base
{
	const SETTING_TABLE = 'setting';

	function __construct(Container $container)
	{
		foreach ($container->getAll() as $key => $value) {
			$this->{$key} = $value;
		}
	}
}
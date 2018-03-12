<?php

namespace Akane\Controller;

use Akane\Core\Container;

class AuthController extends BaseController
{
	public function __construct(Container $container)
	{
		parent::__construct($container);

		if (!$this->auth->isLogin()){
			$this->http->redirect('/user/login');
		}
	}
}
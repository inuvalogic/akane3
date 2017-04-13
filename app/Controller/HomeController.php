<?php

namespace Akane\Controller;

class HomeController extends BaseController
{
	public function indexAction()
	{
		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('page/home')
		));
	}
}
<?php

namespace Akane\Controller;

class PageController extends BaseController
{
	public function notfoundAction()
	{
		echo $this->layout->render('404');
	}

	public function aboutAction()
	{
		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('page/about')
		));
	}
}
<?php

namespace Akane\Controller;

class PageController extends BaseController
{
	public function notfoundAction()
	{
		echo $this->layout->render('404');
	}
}
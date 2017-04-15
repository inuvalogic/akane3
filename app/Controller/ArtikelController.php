<?php

namespace Akane\Controller;

class ArtikelController extends BaseController
{
	public function indexAction()
	{
		$data_artikel = $this->artikelmodel->getAll();

		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('artikel/all', ['data_artikel' => $data_artikel])
		));
	}
}
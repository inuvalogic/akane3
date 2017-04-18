<?php

namespace Akane\Controller;

class ArtikelController extends BaseController
{

	public function indexAction()
	{
		// contoh SQL pada controller dan memakai Helper
		$sql = "SELECT * FROM `" . $this->artikelmodel->getTableName() . "` ORDER BY `id` DESC";
		$data_artikel = $this->artikelmodel->getData($sql);

		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('artikel/all', ['data_artikel' => $data_artikel])
		));
	}

	public function index2Action()
	{
		// contoh load method yang dibuat di model
		$data_artikel = $this->artikelmodel->getAll();

		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('artikel/all', ['data_artikel' => $data_artikel])
		));
	}

	public function index3Action()
	{
		// alternatif dengan self::TABLE_NAME
		$data_artikel = $this->artikelmodel->getAllAlternatif();

		echo $this->layout->render('layout', array(
			'content' => $this->layout->render('artikel/all', ['data_artikel' => $data_artikel])
		));
	}

	public function insertAction()
	{
		// contoh insert artikel baru
		$this->artikelmodel->insert([
			'judul' => 'artikel baru',
			'isi' => 'ini hanya contoh artikel baru'
		]);
	}

	public function updateAction()
	{
		// contoh update artikel id = 1
		$this->artikelmodel->update([
			'judul' => 'aaa',
			'isi' => 'bbb'
		],[
			'id' => 1
		]);
	}

	public function deleteAction()
	{
		// contoh delete artikel id = 4
		$this->artikelmodel->delete(4);
	}
}
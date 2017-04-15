<?php

namespace Akane\Model;

class ArtikelModel extends \Akane\Core\BaseModel
{
	const TABLE_NAME = 'artikel';
	
	public function getAll()
	{
		$sql = "SELECT * FROM `".self::TABLE_NAME."` ORDER BY `id` DESC";
		return $this->getData($sql);
	}
}
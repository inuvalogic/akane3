<?php

namespace Akane\Model;

class ArtikelModel extends \Akane\Core\Base
{
	const TABLE_NAME = 'artikel';
	
	public function getAll()
	{
		$sql = "SELECT * FROM `".self::TABLE_NAME."` ORDER BY `id` DESC";
		$q = $this->db->pdo->prepare($sql);
        $q->execute();
        $row = $q->fetchAll();
        return $row;
	}
}
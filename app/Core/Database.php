<?php

namespace Akane\Core;

use \PDO;

class Database
{
	public $pdo = null;

    public function init()
    {
        try {
            $dbh = new PDO(DB_DRIVER.':host='.DB_HOSTNAME.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD, array(PDO::ATTR_PERSISTENT => true));
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $dbh;
        } catch (\PDOException $e) {
            echo 'Database Error = '.$e->getMessage();
            exit;
        }
    }

    public function close()
    {
        $this->pdo = null;
    }
}
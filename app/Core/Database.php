<?php

namespace Akane\Core;

use \PDO;

class Database
{
	public $pdo = null;

    public function init()
    {
        try {
            $dsn = DB_DRIVER.':host=' . DB_HOSTNAME . ';dbname=' . DB_NAME;
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            );

            if( version_compare(PHP_VERSION, '5.3.6', '<') ){
                if( defined('PDO::MYSQL_ATTR_INIT_COMMAND') ){
                    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES ' . DB_ENCODING;
                }
            }else{
                $dsn .= ';charset=' . DB_ENCODING;
            }

            $dbh = @new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);

            if( version_compare(PHP_VERSION, '5.3.6', '<') && !defined('PDO::MYSQL_ATTR_INIT_COMMAND') ){
                $sql = 'SET NAMES ' . DB_ENCODING;
                $dbh->exec($sql);
            }
        
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